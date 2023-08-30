<?php

namespace App\Service;

use App\Utility\XmlUtils;
use App\Utility\CustomCurl;
use App\Utility\CustomLogger;
use App\Utility\DateUtils;
use App\Enum\Ebay\EbayDetailLevel;
use App\Enum\Ebay\EbayGranularityLevel;

/**
 * The `EbayApiService` class provides methods for making API calls to eBay.
 * 
 * The contained methods are fetching eBay's current timestamp 
 * and storing the actual active ids of a seller's items in a specified file.
 */
class EbayApiService {

    private XmlUtils $xmlUtils;
    private CustomLogger $customLogger;
    private CustomCurl $customCurl;
    private DateUtils $dateUtils;
    private string $apiToken;
    private int $compatLevel;
    private int $siteId;

    /**
     * The '__construct' method initializes properties with corresponding values, either defaults or passed as arguments.
     * 
     * @param XmlUtils $xmlUtils Provides utility methods for working with XML data.
     * @param CustomLogger $customLogger Passes an instance of a custom logging class to use the customized logging implementation.
     * @param CustomCurl $customCurl Passes an instance of a custom cURL class to use the customized cURL implementation.
     * @param DateUtils $dateUtils Passes an Instance of a class to calculate dates.
     * @param string $apiToken Passes the API token required for authentication with an external API. 
     * @param int $compatLevel Specifies the version of the API that the code is compatible with.
     * @param int $siteId Specifies the unique country site on eBay that the code is being used for.
     * 
     * @return void
     */
    public function __construct(XmlUtils $xmlUtils, CustomLogger $customLogger, CustomCurl $customCurl, DateUtils $dateUtils, string $apiToken, int $compatLevel, int $siteId,) {

        $this->xmlUtils = $xmlUtils;
        $this->customLogger = $customLogger;
        $this->customCurl = $customCurl;
        $this->dateUtils = $dateUtils;
        $this->apiToken = $apiToken;
        $this->compatLevel = $compatLevel;
        $this->siteId = $siteId;
    }

    /**
     * The 'getBasicRequestXml' method returns a basic XML request string with the call name and API token for a specified eBay API call. 
     * 
     * Each call will have to add it's own specific elements to this basic XML request.
     * 
     * @param string $callName Specifies the name of the eBay API call to dynamically generate the basic XML request.
     * 
     * @return string XML document string.
     */
    protected function getBasicRequestXml(string $callName): string {

        return <<<XML
        <?xml version="1.0" encoding="utf-8"?>
        <{$callName}Request xmlns="urn:ebay:apis:eBLBaseComponents">
            <RequesterCredentials>
                <eBayAuthToken>$this->apiToken</eBayAuthToken>
            </RequesterCredentials>
            <ErrorLanguage>en_US</ErrorLanguage>
            <WarningLevel>High</WarningLevel>
        </{$callName}Request>
        XML;
    }

    /**
     * The 'getBasicHeaders' method returns an array of non-optional basic headers for any eBay API call.
     * 
     * In addition to the name of the eBay API call, eBay's compatibility level and site ID are also mandatory for each call. 
     * Further optional or mandatory headers may need to be added before executing the request.
     * 
     * @param string $callName Specifies the name of the eBay API call performing the specific operation.
     * 
     * @return array<string|int, mixed> Array of basic non-optional headers.
     */
    protected function getBasicHeaders(string $callName): array {

        return [
            'X-EBAY-API-COMPATIBILITY-LEVEL: ' . $this->compatLevel,
            'X-EBAY-API-CALL-NAME: ' . $callName,
            'X-EBAY-API-SITEID: ' . $this->siteId,
        ];
    }

    /**
     * The 'executeXmlApiCurl' method executes a cURL request with XML data and returns the XML response as string.
     * 
     * @param array $headers        Array of headers to be included in the HTTP request. 
     * @param string $postFields    Contains the data to be sent as body of the HTTP request. 
     * For the 'traditional' eBay APIs it has to include XML or SOAP data, the new APIs take JSON. 
     * This programm requests the traditional 'Trading API' using XML data.
     * 
     * @return string XML string as response of the cURL request.
     * @throws \Exception If there is an error executing the cURL request.
     */
    protected function executeXmlApiCurl(array $headers, string $postFields): string {

        return $this->customCurl->executeCurl($headers, $postFields);
    }

    /**
     * The `getTimestamp` method returns the current eBay timestamp and logs to error log on failure.
     * 
     * There is no need for additional XML elements or headers, as this call will not be recognized by the Trading API. 
     * Nevertheless, the API will return a response stating this failure and including eBay's current timestamp.
     * Like this, the `getTimestamp` method can utilize the same API as all other operations.
     *  
     * @return string eBay timestamp as a string looking like this: 2023-07-27 08:37:45
     * @throws \Exception If there is an error executing the cURL request or processing the response.
     */
    public function getTimestamp(): string {

        // Set basic variables
        $callName = 'GeteBayOfficalTime';
        $xmlRequest = $this->getBasicRequestXml($callName);
        $headers = $this->getBasicHeaders($callName);

        // Try to execute cURL request
        try {

            // Call API, convert response string to XML and get the value of the 'Timestamp' node
            $response = $this->executeXmlApiCurl($headers, $xmlRequest);
            $xmlResponse = simplexml_load_string(trim($response));
            $timestamp = (string) $xmlResponse->Timestamp;

            return $timestamp;
        } catch (\Exception $e) {

            // Log error
            $this->customLogger->errorLog("Failed to fetch eBay timestamp: " . $e->getMessage());

            throw new \Exception("Failed 'GeteBayOfficalTime': " . $e->getMessage());
        }
    }

    /**
     * The `getSellerList` method retrieves a list of a seller's items from eBay based on specified parameters and 
     * saves the results to a file. 
     * 
     * A time window of max 120 days must be given to get all listings either starting OR ending in the specified window. 
     * All active(!) listings of a seller can be retrieved by requesting all listings ending(!) in the time window from 'now' to 'now +120 days'. 
     * 
     * If no level of response details (Granularity Level or Detail Level) is set, the response will only contain id, starting and end time of each listing, 
     * which could be reduced even further to only the id by setting the the output selector to ['ItemID']. 
     * However, as soon as a level of response details is set, pagination is mandatory and the amount of data schould be reduced using the output selector in order to enhance performance. 
     * That means, if e.g. the listings' ids and titles are needed, the granularity level has to be set to at least 'EbayGranularityLevel::COARSE' and 
     * the output selector should correspondingly be set to ['ItemID', 'Title', 'HasMoreItems', 'PageNumber'] while eBay's max of 200 entries per page can still work fine. 
     * But if e.g. all for this available details are needed, the detail level has to be set to 'EbayDetailLevel::ALL' and the output selector has to stay 'null' 
     * while the reduction of the entries per page should be adjusted very smart towards the lower end!
     * 
     * Note: Despite of further filter options not shown here, unfortunately, NO level of this call's response details returns ALL details of a listing. 
     * Therefore, this function is used only once at the very first start to retrieve and store all ids of a seller's actual active listings, 
     * while the complete list of details for each listing will be retrieved by using eBay's 'GetItem' call.
     * 
     * @param string $initialEbayFile File path where the initial eBay response will be stored for further processing (Default: __DIR__ . '/../../data/ebay/initial_active_ids.xml'). 
     * @param string|null $endTimeFrom Starting time of the time window for retrieving a seller's listings filtered by their end time.
     * If not provided, it will default to 'now'. 
     * @param string|null $endTimeTo End time of the time window for retrieving a seller's listings filtered by their end time. 
     * If not provided, it will default to a calculated value of the `endTimeFrom` parameter +120 days (format: UTC +0, no DST!). 
     * @param int|null $entriesPerPage Optional number of items to be returned per page. Only used in case of provided detail or granularity level, if unset it defaults to 200 (eBay's max).
     * @param EbayDetailLevel|EbayGranularityLevel|null $setLevel Optionally used to specify the detail or granularity level for the eBay API response (Default: 'null').
     * @param array[string]|null $outputSelector Optionally specifies the fields to retrieve from the eBay API response (Default: 'null'). 
     * 
     * @return bool
     * @throws \Exception If result could not be written to the file.
     */
    public function storeSellerList(
        string $initialEbayFile = __DIR__ . '/../../data/ebay/initial_active_ids.xml',
        ?string $endTimeFrom = null,
        ?string $endTimeTo = null,
        ?int $entriesPerPage = null,
        EbayDetailLevel|EbayGranularityLevel|null $setLevel = null,
        ?array $outputSelector = null,
    ): bool {

        // Set basic variables
        $callName = 'GetSellerList';
        $xmlRequest = $this->getBasicRequestXml($callName);
        $headers = $this->getBasicHeaders($callName);
        $endTimeFrom = $endTimeFrom !== null ? $endTimeFrom : $this->getTimestamp();
        $xmlAddition = [];

        // Create a string with all additional XML parameters necessary for this specific call
        $xmlAddition['EndTimeFrom'] = $endTimeFrom;

        if (!isset($endTimeTo))
            $endTimeTo = $this->dateUtils->calculateNewUtcTimestamp($endTimeFrom, '+', '120', 'D');
        $xmlAddition['EndTimeTo'] = $endTimeTo;

        if ($outputSelector || $setLevel) {

            if ($setLevel instanceof EbayDetailLevel || $setLevel instanceof EbayGranularityLevel) {

                //Create xml node for entries per page for necessary pagination loop (max result per call = 200)
                $entriesPerPage = $entriesPerPage !== null ? $entriesPerPage : 200;
                $xmlAddition['Pagination'] = ['EntriesPerPage' => $entriesPerPage];

                // Create xml node for detail or granularity level
                $name = $setLevel instanceof EbayDetailLevel ? 'DetailLevel' : 'GranularityLevel';
                $xmlAddition[$name] = $setLevel->value;
            }

            if ($outputSelector !== null) {

                $selectorStr = '';
                foreach ($outputSelector ?? [] as $selector)
                    $selectorStr .= "$selector,";
                $selectorStr = trim($selectorStr, ',');

                $xmlAddition['OutputSelector'] = (string)$selectorStr;
            }
        }

        // Execute a cURL request (corresponding to the desired detail/granularity level) and save the result(s)
        if (!isset($setLevel)) {

            // Include the additional elements in the xml request string
            $xmlRequest = $this->xmlUtils->addNodesToXml($xmlRequest, $xmlAddition);

            // Try to execute cURL request and store the response
            try {

                $initialFileHandle = fopen($initialEbayFile, 'w');

                // Initialize curl with necessary options array set and store structured XML string
                $response = $this->executeXmlApiCurl($headers, $xmlRequest);
                fwrite($initialFileHandle, $response, strlen($response));
                fclose($initialFileHandle);
            } catch (\Exception $e) {

                // Log error
                $this->customLogger->errorLog("Failed call of eBay GetSellerList (without detail levels): " . $e->getMessage());

                throw new \Exception("Failed 'GetSellerList': " . $e->getMessage());
            }
        } else {

            //Set initials for necessary pagination loop
            $page = 0;
            $hasMoreItems = false;

            do {

                $page++;

                // Add pagination details to call-specific XML request 
                $xmlAddition['Pagination']['PageNumber'] = $page;

                // Include the additional elements in the xml request string
                $xmlRequest = $this->xmlUtils->addNodesToXml($xmlRequest, $xmlAddition);

                // Try to execute cURL request and store the response
                try {

                    // Create numbered filenames for the paginated response
                    $inititialPaginatedEbayFile = str_replace('.xml', sprintf("%'.03d", $page) . '.xml', $initialEbayFile);
                    $initialFileHandle = fopen($inititialPaginatedEbayFile, 'w');

                    // Initialize curl with necessary options array set and store structured XML string
                    $response = $this->executeXmlApiCurl($headers, $xmlRequest);
                    $xmlResponse = simplexml_load_string(trim($response));
                    fwrite($initialFileHandle, $response, strlen($response));
                    fclose($initialFileHandle);

                    // Check if further pages/items
                    $hasMoreItems = strtolower((string)$xmlResponse->HasMoreItems) === 'true';
                } catch (\Exception $e) {

                    // Log error
                    $this->customLogger->errorLog("Failed call of eBay GetSellerList (page $page): " . $e->getMessage());

                    throw new \Exception("Failed 'GetSellerList': " . $e->getMessage());
                }
            } while ($hasMoreItems);
        }

        return true;
    }
}

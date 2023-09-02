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
     * Each call might have to add it's own specific elements to this basic XML request.
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
     * @return array<string> Array of basic non-optional headers.
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
     * @param array $headers Array of headers to be included in the HTTP request. 
     * @param string $postFields Contains the data to be sent as body of the HTTP request. 
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
            $this->customLogger->errorLog('Failed to fetch eBay timestamp: ' . $e->getMessage());

            throw new \Exception("Failed 'GeteBayOfficalTime': " . $e->getMessage());
        }
    }

    /**
     * The `getSellerList` method retrieves a list of a seller's items from eBay based on specified parameters and 
     * returns an array of filtered items with details. 
     * 
     * A time window of max 120 days must be given to get all listings either starting OR ending in the specified window. 
     * All active(!) listings of a seller can be retrieved by requesting all listings ending(!) in the time window from 'now' to 'now +120 days'. 
     * 
     * If no level of response details (Granularity Level or Detail Level) is set, the response will only contain id, starting and end time of each listing, 
     * which could be reduced even further to only the id by setting the the output selector to ['ItemID']. Requesting other fields to return, e.g. 'Title', will simply be ignored.
     * 
     * However, as soon as a level of response details is set, pagination is mandatory and the amount of data schould be reduced using the output selector in order to enhance performance. 
     * That means, if e.g. the listings' ids and titles are needed, the granularity level has to be set to at least 'EbayGranularityLevel::COARSE' and 
     * the output selector should correspondingly be set to ['ItemID', 'Title', 'HasMoreItems', 'PageNumber'] while eBay's max of 200 entries per page can still work fine. 
     * But if e.g. all in this call available details are needed, the detail level has to be set to 'EbayDetailLevel::ALL' and the output selector has to stay 'null' 
     * while the reduction of the entries per page should be adjusted very smart towards the lower end!
     * 
     * Note: Despite of further filter options not shown here, unfortunately, NO level of this call's response details returns ALL details of a listing. 
     * Therefore, this function is used only once at the very first start to retrieve all ids of a seller's actual active listings, 
     * while the complete list of details for each listing will be retrieved by using eBay's 'GetItem' call.
     * 
     * @param string|null $endTimeFrom Starting time of the time window for retrieving a seller's listings filtered by their end time.
     * Required format: ISO 8601. If not provided, it will default to 'now' by requesting and using eBay's timestamp. 
     * @param string|null $endTimeTo End time of the time window for retrieving a seller's listings filtered by their end time. 
     * Required format: ISO 8601. If not provided, it will default to a calculated value of the `endTimeFrom` parameter +120 days (format: UTC +0, no DST!). 
     * @param int|null $entriesPerPage Optional/Mandatory number of items to be returned per page. Only to be used in case of provided detail or granularity level, if unset it defaults to 200, as this is eBay's max.
     * @param EbayDetailLevel|EbayGranularityLevel|null $setLevel Optionally used to specify the detail or granularity level for the eBay API response (Default: 'null').
     * @param array<string>|null $outputSelector Optionally specifies the fields to retrieve from the eBay API response (Default: 'null'). 
     * 
     * @return array<string> Array of items with desired details.
     * @throws \Exception If result could not be written to the file.
     */
    public function getSellerList(
        ?string $endTimeFrom = null,
        ?string $endTimeTo = null,
        ?int $entriesPerPage = null,
        EbayDetailLevel|EbayGranularityLevel|null $setLevel = null,
        ?array $outputSelector = null,
    ): array {

        // Set basic variables
        $callName = 'GetSellerList';
        $xmlRequest = $this->getBasicRequestXml($callName);
        $headers = $this->getBasicHeaders($callName);
        $xmlAddition = [];

        // Create an array with all additional XML parameters necessary for this specific call
        $xmlAddition['EndTimeFrom'] = $endTimeFrom ?? $this->getTimestamp();
        $endTimeTo ?? $xmlAddition['EndTimeTo'] = $this->dateUtils->calculateNewUtcTimestamp($xmlAddition['EndTimeFrom'], '+', '120', 'D');

        if (isset($setLevel)) {

            // Add array element for entries per page for the necessary pagination loop (default = eBay's max entries per page = 200)
            $xmlAddition['Pagination'] = ['EntriesPerPage' => $entriesPerPage ?? 200];

            if ($setLevel instanceof EbayDetailLevel || $setLevel instanceof EbayGranularityLevel) {

                // Add array element for detail or granularity level
                $name = $setLevel instanceof EbayDetailLevel ? 'DetailLevel' : 'GranularityLevel';
                $xmlAddition[$name] = $setLevel->value;
            }
        }

        if (isset($outputSelector)) {

            // Create comma separated string with desired output fields
            $selectorStr = '';
            foreach ($outputSelector ?? [] as $selector)
                $selectorStr .= "$selector,";
            $selectorStr = trim($selectorStr, ',');

            // Add the created string as value of the array element for the output selector
            $xmlAddition['OutputSelector'] = $selectorStr;
        }

        // Execute a cURL request (corresponding to the desired detail/granularity level) and add the ids to an array
        $itemArray = [];
        if (!isset($setLevel)) {

            // Add the additional elements to the xml request string
            $xmlRequest = $this->xmlUtils->addNodesToXml($xmlRequest, $xmlAddition);

            // Try to execute cURL request and fill the array
            try {

                // Initialize curl with necessary options array set
                $response = $this->executeXmlApiCurl($headers, $xmlRequest);
                $xmlResponse = simplexml_load_string($response);
                foreach ($xmlResponse->ItemArray->Item as $item) {
                    $itemArray[(string)$item->ItemID] = $item;
                }
            } catch (\Exception $e) {

                // Log error
                $this->customLogger->errorLog('Failed call of eBay GetSellerList (without detail levels): ' . $e->getMessage());

                throw new \Exception("Failed 'GetSellerList': " . $e->getMessage());
            }
        } else {

            //Set initials for necessary pagination loop
            $page = 0;
            $hasMoreItems = false;

            do {
                $page++;

                // Add further pagination details to the array with additional XML nodes
                $xmlAddition['Pagination']['PageNumber'] = $page;

                // Add the additional elements to the call-specific XML request string
                $xmlRequest = $this->xmlUtils->addNodesToXml($xmlRequest, $xmlAddition);

                // Try to execute cURL request and fill the array
                try {

                    // Initialize curl with necessary options array set 
                    $response = $this->executeXmlApiCurl($headers, $xmlRequest);
                    $xmlResponse = simplexml_load_string(trim($response));
                    foreach ($xmlResponse->ItemArray->Item as $item) {
                        $itemArray[(string)$item->ItemID] = $item;
                    }

                    // Check if further pages/items to be called exist 
                    $hasMoreItems = strtolower((string)$xmlResponse->HasMoreItems) === 'true';
                } catch (\Exception $e) {

                    // Log error
                    $this->customLogger->errorLog("Failed call of eBay GetSellerList (page $page): " . $e->getMessage());

                    throw new \Exception("Failed 'GetSellerList': " . $e->getMessage());
                }
            } while ($hasMoreItems);
        }

        return $itemArray;
    }

    /**
     * The 'getItemDetails' method retrieves details of a listing from eBay by using the listings ID. 
     * 
     * Regardless of the optional setting of a detail level to return, 
     * several details of a listing's item will only be retrieved by specifying the request correspondingly. 
     * This App uses by default the detail level 'ReturnAll' with the additional requests for item specifics 
     * and the compatibility list, which is used e.g. to sell car parts.
     * 
     * @param string $itemId Unique identifier of the item you want to retrieve details for.
     * @param bool $itemCompatibilityList Optional flag indicating whether or not to include the item compatibility list (Default: true). 
     * @param bool $itemSpecifics Optional flag indicating whether or not to include the item specifics (Default: true). 
     * @param EbayDetailLevel $detailLevel Optional to specify the level of item details to be returned in the response (Default: 'ReturnAll'. 
     * 
     * @return string The XML string of the specified item with all requested details.
     * @throws \Exception If there is an error executing the cURL request.
     */
    public function getItemDetails(
        string $itemId,
        ?bool $itemCompatibilityList = true,
        ?bool $itemSpecifics = true,
        ?EbayDetailLevel $detailLevel = EbayDetailLevel::ALL,
    ): string {

        // Set basic variables
        $callName = 'GetItem';
        $xmlRequest = $this->getBasicRequestXml($callName);
        $headers = $this->getBasicHeaders($callName);
        $xmlAddition = [];

        // Create an array with all additional XML parameters necessary for this specific call
        $xmlAddition['ItemID'] = trim((string)$itemId);
        $itemCompatibilityList && $xmlAddition['IncludeItemCompatibilityList'] = 'true';
        $itemSpecifics && $xmlAddition['IncludeItemSpecifics'] = 'true';
        $detailLevel && $xmlAddition['DetailLevel'] = $detailLevel->value;

        // Add the additional elements to the xml request string
        $xmlRequest = $this->xmlUtils->addNodesToXml($xmlRequest, $xmlAddition);

        // Try to execute cURL request and return the response
        try {

            // Initialize curl with necessary options array set and return the response
            return $this->executeXmlApiCurl($headers, $xmlRequest);
        } catch (\Exception $e) {

            // Log error
            $this->customLogger->errorLog("Failed call of eBay GetItem (for ID: $itemId)" . $e->getMessage());

            throw new \Exception("Failed 'GetItem': " . $e->getMessage());
        }
    }

    /**
     * The `getSellerEvents` method requests a seller's listing events based on specified parameters.
     * 
     * A sellers listings can be filtered for a time window using the start, end or modified time of the listings. 
     * With the target in mind to auto-update a database with existing entries, the 'ModTime' with the 'NewItemFilter' are used. 
     * However, the ending time of the time window is left 'null', because like this each call gets all modifications until 'now' anyway. 
     * As all of an item's details are retrieved by 'GetItem', the detail level is set to 'null' 
     * and only id and the listing status are selected to be returned.
     * 
     * @param string $modTimeFrom Starting time of the time window for retrieving a seller's events 
     * filtered by the existence of modifications. Required format: ISO 8601. 
     * @param string|null $modTimeTo Optional end time of the time window for retrieving a seller's events 
     * filtered by the existence of modifications. Required format: ISO 8601. 
     * If not provided, the API will automatically respond with all events until 'now'.
     * @param bool $newItemFilter Optional flag indicating whether or not to filter the results to only include modified items. 
     * If set to `false` or not provided, ALL items will be returned.
     * @param EbayDetailLevel|null $detailLevel Optionally used to specify the detail level for the eBay API response (Default: 'null').
     * @param array<string>|null $outputSelector Optionally specifies the fields to retrieve from the eBay API response (Default: ['ItemID', 'ListingStatus']). 
     * 
     * @return string The XML string with the filtered items within the requested time window with minimal details.
     * @throws \Exception If there is an error executing the cURL request.
     */
    function getSellerEvents(
        string $modTimeFrom,
        ?string $modTimeTo = null,
        ?bool $newItemFilter = true,
        ?EbayDetailLevel $detailLevel = null,
        ?array $outputSelector = ['ItemID', 'ListingStatus'],
    ): string {

        // Set basic variables
        $callName = 'GetSellerEvents';
        $xmlRequest = $this->getBasicRequestXml($callName);
        $headers = $this->getBasicHeaders($callName);
        $xmlAddition = [];

        // Create an array with all additional XML parameters necessary for this specific call
        $xmlAddition['ModTimeFrom'] = $modTimeFrom;
        $modTimeTo && $xmlAddition['ModTimeTo'] =  $modTimeTo;
        $newItemFilter && $xmlAddition['NewItemFilter'] = 'true';
        $detailLevel && $xmlAddition['DetailLevel'] = $detailLevel;

        if (isset($outputSelector)) {

            // Create comma separated string with desired output fields
            $selectorStr = '';
            foreach ($outputSelector ?? [] as $selector)
                $selectorStr .= "$selector,";
            $selectorStr = trim($selectorStr, ',');

            // Add the created string as value of the array element for the output selector
            $xmlAddition['OutputSelector'] = $selectorStr;
        }

        // Add the additional elements to the xml request string
        $xmlRequest = $this->xmlUtils->addNodesToXml($xmlRequest, $xmlAddition);

        // Try to execute cURL request and return the response
        try {

            // Initialize curl with necessary options array set and return the response
            return $this->executeXmlApiCurl($headers, $xmlRequest);
        } catch (\Exception $e) {

            // Log error
            $this->customLogger->errorLog('Failed call of eBay GetSellerEvents ' . $e->getMessage());

            throw new \Exception("Failed 'GetSellerEvents': " . $e->getMessage());
        }
    }
}

<?php

namespace App\Service;

use App\Utility\CustomCurl;
use App\Utility\CustomLogger;

/**
 * The `EbayApiService` class provides methods for making API calls to eBay.
 * 
 * The contained method is fetching eBay's current timestamp.
 */
class EbayApiService {
    private CustomLogger $customLogger;
    private CustomCurl $customCurl;
    private string $apiToken;
    private int $compatLevel;
    private int $siteId;

    /**
     * The '__construct' method initializes properties with corresponding values, either defaults or passed as arguments.
     * 
     * @param CustomLogger $customLogger Passes an instance of a custom logging class to use the customized logging implementation.
     * @param CustomCurl $customCurl Passes an instance of a custom cURL class to use the customized cURL implementation.
     * @param string $apiToken Passes the API token required for authentication with an external API. 
     * @param int $compatLevel Specifies the version of the API that the code is compatible with.
     * @param int $siteId Specifies the unique country site on eBay that the code is being used for.
     * 
     * @return void
     */
    public function __construct(CustomLogger $customLogger, CustomCurl $customCurl, string $apiToken, int $compatLevel, int $siteId,) {

        $this->customLogger = $customLogger;
        $this->apiToken = $apiToken;
        $this->compatLevel = $compatLevel;
        $this->siteId = $siteId;
        $this->customCurl = $customCurl;
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
     * @return array Array of basic non-optional headers.
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
     * The `getTimestamp` method retrieves the current eBay timestamp and logs the result to either the info or error log.
     * 
     * There is no need for additional XML elements or headers, as this call will not be recognized by the Trading API. 
     * Nevertheless, the API will return a response stating this failure and including eBay's current timestamp.
     * Like this, the `getTimestamp` method can utilize the same API as all other operations.
     *  
     * @return string eBay timestamp as a string looking like this: 2023-07-27 08:37:45
     * @throws \Exception If there is an error executing the cURL request or processing the response.
     */
    public function getTimestamp() {

        // Set basic variables
        $callName = 'GeteBayOfficalTime';
        $xmlRequest = $this->getBasicRequestXml($callName);
        $headers = $this->getBasicHeaders($callName);

        // Try to execute cURL request
        try {

            $response = $this->executeXmlApiCurl($headers, $xmlRequest);
            $xmlResponse = simplexml_load_string(trim($response));
            $timestamp = (string) $xmlResponse->Timestamp;

            // Log success
            $this->customLogger->infoLog("eBay timestamp: {$timestamp}");

            return $timestamp;
        } catch (\Exception $e) {

            // Log error
            $this->customLogger->errorLog("Failed to fetch eBay timestamp: " . $e->getMessage());

            throw new \Exception("Failed 'GeteBayOfficalTime': " . $e->getMessage());
        }
    }
}

<?php

namespace App\Service;

class EbayApiService {
    protected $customLogger;
    protected $apiToken;
    protected $compatLevel;
    protected $siteId;
    protected $customCurl;

    public function __construct($customLogger, $apiToken, $compatLevel, $siteId, $customCurl) {

        $this->customLogger = $customLogger;
        $this->apiToken = $apiToken;
        $this->compatLevel = $compatLevel;
        $this->siteId = $siteId;
        $this->customCurl = $customCurl;
    }

    // Create the base for XML requests using HEREDOC (each call will have to add specific elements to the XML request)
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

    // Get basic (non-optional) headers (additional headers are optional or mandatory, depending on the call)
    protected function getBasicHeaders(string $callName): array {

        return [
            'X-EBAY-API-COMPATIBILITY-LEVEL: ' . $this->compatLevel,
            'X-EBAY-API-CALL-NAME: ' . $callName,
            'X-EBAY-API-SITEID: ' . $this->siteId,
        ];
    }

    // Execute cURL with standard parameters for eBay's XML API
    protected function executeXmlApiCurl(array $headers, string $postFields): string {

        return $this->customCurl->execute_curl($headers, $postFields);
    }

    // Get eBay's current timestamp (the timestamp will be included in the response stating a failure, due to the wrong call for this API)
    public function getTimestamp() {

        // Set basics (what is sufficient for this call)
        $callName = 'GeteBayOfficalTime';
        $xmlRequest = $this->getBasicRequestXml($callName);
        $headers = $this->getBasicHeaders($callName);

        // Initialize cURL with HTTP POST
        try {

            $response = $this->executeXmlApiCurl($headers, $xmlRequest);
            $xmlResponse = simplexml_load_string(trim($response));
            $timestamp = (string) $xmlResponse->Timestamp;

            // Log success
            $this->customLogger->info_log("eBay timestamp: {$timestamp}");

            return $timestamp;
        } catch (\Exception $e) {

            // Log error
            $this->customLogger->error_log("Failed to fetch eBay timestamp: " . $e->getMessage());

            throw new \Exception("Failed 'GeteBayOfficalTime': " . $e->getMessage());
        }
    }
}

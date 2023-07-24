<?php

namespace App\Service;

class EbayApiService {
    protected $apiUrl;
    protected $apiToken;
    protected $compatLevel;
    protected $siteId;

    public function __construct($apiUrl, $apiToken, $compatLevel, $siteId) {

        // Assign parameters
        $this->apiUrl = $apiUrl;
        $this->apiToken = $apiToken;
        $this->compatLevel = $compatLevel;
        $this->siteId = $siteId;
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

    // Execute an XML API call using POST (returns XML string)
    protected function executeCurl(array $headers, string $xmlRequest): string {

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $xmlRequest,
            CURLOPT_RETURNTRANSFER => 1,
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    // Get eBay's current timestamp (the timestamp will be included in the response stating a failure, due to the wrong call for this API)
    public function getEbayTimestamp() {

        // Set defaults
        $callName = 'GeteBayOfficalTime';
        $xmlRequest = $this->getBasicRequestXml($callName);
        $headers = $this->getBasicHeaders($callName);

        // Initialize curl and convert the response
        $response = $this->executeCurl($headers, $xmlRequest);
        $xmlResponse = simplexml_load_string($response);

        return (string) $xmlResponse->Timestamp;
    }
}

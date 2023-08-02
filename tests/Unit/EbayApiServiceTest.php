<?php

namespace Tests\Unit;

use App\Service\EbayApiService;
use App\Utility\CustomCurl;
use App\Utility\CustomLogger;
use Codeception\Test\Unit;

/**
 * The `EbayApiServiceTest` is a unit test class of the EbayApiService` class 
 * 
 * It tests the functionality of getting the actual eBay timestamp. 
 */
class EbayApiServiceTest extends Unit {

    protected $tester;
    private $customCurl;
    private $customLogger;
    private $ebayApiService;

    /**
     * The '_before' function is used to set up the necessary environment for running tests
     * 
     * In this case a reusable mock of the 'CustomLogger' class is created.
     */
    protected function _before() {

        $this->customLogger = $this->makeEmpty(CustomLogger::class);
    }

    /**
     * The 'testGetTimestampReturnsTimestamp' function tests the `getTimestamp` method
     * 
     * It tests whether a string representing the eBay timestamp is returned from the API call.
     */
    public function testGetTimestampReturnsTimestamp() {

        // Arrange mock objects for the 'CustomCurl' class returning an XML string and the 'EbayApiService' class
        $this->customCurl = $this->make(CustomCurl::class, [
            'executeCurl' => function () {
                return '<?xml version="1.0" encoding="UTF-8"?>
                <GeteBayOfficialTimeResponse xmlns="urn:ebay:apis:eBLBaseComponents">
                    <Timestamp>2023-08-01T00:00:00.000Z</Timestamp>
                </GeteBayOfficialTimeResponse>';
            }
        ]);

        $this->ebayApiService = new EbayApiService($this->customLogger, 'apiToken', 'compatLevel', 'siteId', $this->customCurl);

        // Act
        $result = $this->ebayApiService->getTimestamp();

        // Assert that the variable the correct string is returned
        $this->assertIsString($result);
        $this->assertEquals('2023-08-01T00:00:00.000Z', $result);
    }

    /**
     * The 'testGetTimestampReturnsTimestamp' function tests the `getTimestamp` method
     * 
     * It tests wether an exception is thrown after a cURL error of the API call. 
     */
    public function testGetTimestampThrowsExceptionOnCurlError() {

        // Arrange mock objects for the 'CustomCurl' class throwing an Exception and the 'EbayApiService' class 
        $this->customCurl = $this->make(CustomCurl::class, [
            'executeCurl' => function () {
                throw new \Exception('cURL ERROR');
            }
        ]);

        $this->ebayApiService = new EbayApiService($this->customLogger, 'apiToken', 'compatLevel', 'siteId', $this->customCurl);

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Failed 'GeteBayOfficalTime': cURL ERROR");

        // Act
        $this->ebayApiService->getTimestamp();
    }
}

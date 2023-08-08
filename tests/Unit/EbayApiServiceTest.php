<?php

namespace Tests\Unit;

use App\Service\EbayApiService;
use App\Utility\CustomCurl;
use App\Utility\CustomLogger;
use Codeception\Test\Unit;

/**
 * The `EbayApiServiceTest` is a unit test class of the 'EbayApiService` class 
 * 
 * Tests the functionality of getting the current eBay timestamp. 
 */
class EbayApiServiceTest extends Unit {

    protected $tester;
    private $customCurl;
    private $customLogger;
    private $ebayApiService;

    /**
     * Sets up the test environment by creating a mock object of the 'CustomLogger' class.
     */
    protected function _before() {

        $this->customLogger = $this->makeEmpty(CustomLogger::class);
    }

    /**
     * Tests the `getTimestamp` method of the `EbayApiService` class 
     * whether it returns the correct XML element 'Timestamp' as string
     * with mocked 'CustomLogger' and 'CustomCurl' classes. 
     */
    public function testGetTimestampReturnsTimestamp() {

        // Arrange mock objects for the 'CustomCurl' class returning an XML string
        $this->customCurl = $this->makeEmpty(CustomCurl::class, ['executeCurl' => function () {
            return '<?xml version="1.0" encoding="UTF-8"?>
                <GeteBayOfficialTimeResponse xmlns="urn:ebay:apis:eBLBaseComponents">
                    <Timestamp>2023-08-01 00:00:00</Timestamp>
                </GeteBayOfficialTimeResponse>';
        }]);

        // Create a new 'EbayApiService' with the two mocked objects and random values for the other parameters 
        $this->ebayApiService = new EbayApiService($this->customLogger, $this->customCurl, '9', 1, 1,);

        // Act
        $result = $this->ebayApiService->getTimestamp();

        // Assert that the variable the correct string is returned
        $this->assertIsString($result);
        $this->assertEquals('2023-08-01 00:00:00', $result);
        $this->assertNotEquals('Timestamp', $result);
    }

    /**
     * Tests the `getTimestamp` method of the `EbayApiService` class 
     * whether an exception is thrown after a cURL error of the API call
     * with mocked 'CustomLogger' and 'CustomCurl' classes. 
     */
    public function testGetTimestampThrowsExceptionOnCurlError() {

        // Arrange mock objects for the 'CustomCurl' class throwing an Exception
        $this->customCurl = $this->make(CustomCurl::class, ['executeCurl' => function () {
            throw new \Exception('cURL ERROR:');
        }]);

        // Create a new 'EbayApiService' with the two mocked objects and random values for the other parameters 
        $this->ebayApiService = new EbayApiService($this->customLogger, $this->customCurl, 9, '1', 1,);

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Failed 'GeteBayOfficalTime': cURL ERROR");

        // Act
        $this->ebayApiService->getTimestamp();
    }
}

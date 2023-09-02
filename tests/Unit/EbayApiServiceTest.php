<?php

namespace Tests\Unit\Service;

use App\Enum\Ebay\EbayGranularityLevel;
use App\Service\EbayApiService;
use App\Utility\CustomCurl;
use App\Utility\CustomLogger;
use App\Utility\DateUtils;
use App\Utility\XmlUtils;
use Codeception\Test\Unit;

/**
 * The `EbayApiServiceTest` is a unit test class of the 'EbayApiService` class 
 * 
 * Tests the functionality of 
 * getting the current eBay timestamp and 
 * writing initially the actual active ids of a seller's items to a specified file. 
 */
class EbayApiServiceTest extends Unit {

    protected $tester;
    private $xmlUtils;
    private $customCurl;
    private $customLogger;
    private $dateUtils;
    private $nonWritablePath;
    private $ebayApiService;

    /**
     * Sets up the necessary environment for running tests by cleaning up the test directory, 
     * defining paths for writable files, creating a non-writable test file
     * and initializing an empty mock for every class except the `EbayApiService` class.
     * 
     * The `EbayApiService` class has it's own function, so that each test only needs to adjust the needed objects.
     */
    protected function _before() {

        // Clean up the directory of the test files
        $this->tester->cleanDir(codecept_data_dir());

        // Create mock instances of all classes except 'EbayApiService'
        $this->xmlUtils = $this->makeEmpty(XmlUtils::class);
        $this->customLogger = $this->makeEmpty(CustomLogger::class);
        $this->customCurl = $this->makeEmpty(CustomCurl::class);
        $this->dateUtils = $this->makeEmpty(DateUtils::class);

        // Define the temporary path for a non-writable test log file and set permissions to read-only simulating a non-writable file
        $this->nonWritablePath = codecept_data_dir('non_writable.xml');
        touch($this->nonWritablePath);
        chmod($this->nonWritablePath, 0444);
    }

    /**
     * Cleans the codecept_data_dir directory after each test by deleting all files.
     */
    protected function _after() {

        $this->tester->cleanDir(codecept_data_dir());
    }


    /**
     * Initializes an instance of the EbayApiService class with mocked objects and random
     * values for the other parameters to keep the tests readable.
     */
    private function initializeEbayApiService() {

        $this->ebayApiService = new EbayApiService($this->xmlUtils, $this->customLogger, $this->customCurl, $this->dateUtils, 9, 1, 1);
    }


    /**
     * Tests the `getTimestamp` method of the `EbayApiService` class 
     * whether it returns the correct XML element 'Timestamp' as string
     * with mocked 'CustomLogger' and 'CustomCurl' classes. 
     * 
     * @template RealInstanceType of object (avoids type error of 'CustomCurl')
     */
    public function testGetTimestampReturnsTimestamp() {

        // Arrange mock object for the 'CustomCurl' class returning an XML string
        $this->customCurl = $this->makeEmpty(CustomCurl::class, ['executeCurl' => function () {
            return '<?xml version="1.0" encoding="UTF-8"?>
                <GeteBayOfficialTimeResponse xmlns="urn:ebay:apis:eBLBaseComponents">
                    <Timestamp>2023-08-01 00:00:00</Timestamp>
                </GeteBayOfficialTimeResponse>';
        }]);
        $this->initializeEbayApiService();

        // Act
        $result = $this->ebayApiService->getTimestamp();

        // Assert that the correct string is returned
        $this->assertIsString($result);
        $this->assertEquals('2023-08-01 00:00:00', $result);
        $this->assertNotEquals('Timestamp', $result);
    }

    /**
     * Tests the `getTimestamp` method of the `EbayApiService` class 
     * whether an exception is thrown after a cURL error of the API call
     * with mocked 'CustomLogger' and 'CustomCurl' classes. 
     * 
     * @template RealInstanceType of object (avoids type error of 'CustomCurl')
     */
    public function testGetTimestampThrowsExceptionOnCurlError() {

        // Arrange mock objects for the 'CustomCurl' class throwing an Exception
        $this->customCurl = $this->make(CustomCurl::class, ['executeCurl' => function () {
            throw new \Exception('Error Message');
        }]);
        $this->initializeEbayApiService();

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error Message');

        // Act
        $this->ebayApiService->getTimestamp();
    }

    /**
     * Tests the 'storeSellerList' method of the 'EbayApiService' class 
     * whether the items of the XML response are successfully added to the returned array
     * with no other parameters specified.
     * 
     * @template RealInstanceType of object (avoids type error of 'CustomCurl')
     */
    public function testStoreSellerListReturnsArrayWithNoParamsSpecified() {

        // Arrange mock objects for the 'CustomCurl' class returning one page with an XML string with one item
        $this->customCurl = $this->makeEmpty(CustomCurl::class, ['executeCurl' => function () {
            return '<GetSellerListResponse xmlns="urn:ebay:apis:eBLBaseComponents">
                    <Timestamp>2023-08-28T20:04:38.705Z</Timestamp>
                    <Ack>Success</Ack>
                    <Version>1271</Version>
                    <Build>E1271_CORE_APISELLING_19187369_R1</Build>
                    <PaginationResult>
                        <TotalNumberOfEntries>1</TotalNumberOfEntries>
                    </PaginationResult>
                    <ItemArray>
                        <Item>
                            <ItemID>111111111111</ItemID>
                            <ListingDetails>
                                <StartTime>2022-06-29T05:04:15.000Z</StartTime>
                                <EndTime>2023-08-29T05:04:15.000Z</EndTime>
                            </ListingDetails>
                        </Item>
                </ItemArray>
                <ReturnedItemCountActual>1</ReturnedItemCountActual>
            </GetSellerListResponse>';
        }]);
        $this->initializeEbayApiService();

        // Act
        $result = $this->ebayApiService->storeSellerList();

        // Assert that a non-empty array is returned with the expected number of elements and the expected content.
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey('111111111111', $result);
    }

    /**
     * Tests the 'storeSellerList' method of the 'EbayApiService' class 
     * whether the items of the XML response are successfully added to the returned array
     * with all parameters specified.
     * 
     * @template RealInstanceType of object (avoids type error of 'CustomCurl')
     */
    public function testStoreSellerListReturnsArrayOfOneResultPageWithAllParamsSpecified() {

        // Arrange mock objects for the 'CustomCurl' class returning one page with an XML string with two items
        $this->customCurl = $this->makeEmpty(CustomCurl::class, ['executeCurl' => function () {
            return '<GetSellerListResponse xmlns="urn:ebay:apis:eBLBaseComponents">
                <Timestamp>2023-08-29T20:46:25.972Z</Timestamp>
                <Ack>Success</Ack>
                <Version>1271</Version>
                <Build>E1271_CORE_APISELLING_19187371_R1</Build>
                <HasMoreItems>false</HasMoreItems>
                <ItemArray>
                    <Item>
                        <ItemID>111111111111</ItemID>
                        <SellingStatus>
                            <HighBidder/>
                        </SellingStatus>
                        <ShippingDetails/>
                        <Title>ABC</Title>
                    </Item>
                    <Item>
                        <ItemID>222222222222</ItemID>
                        <SellingStatus>
                            <HighBidder/>
                        </SellingStatus>
                        <ShippingDetails/>
                        <Title>BCD</Title>
                    </Item>
                </ItemArray>
                <PageNumber>1</PageNumber>
            </GetSellerListResponse>';
        }]);
        $this->initializeEbayApiService();

        // Act
        $result = $this->ebayApiService->storeSellerList(
            '2023-08-28T19:04:38.705Z',
            '2023-08-28T20:04:38.705Z',
            50,
            EbayGranularityLevel::COARSE,
            ['ItemID', 'Title', 'HasMoreItems', 'PageNumber']
        );

        // Assert that a non-empty array is returned with the expected number of elements and the expected content.
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertCount(2, $result);
        $this->assertArrayHasKey('111111111111', $result);
        $this->assertArrayHasKey('222222222222', $result);
    }

    /**
     * Tests the 'storeSellerList' method of the 'EbayApiService' class 
     * whether the items of the XML response are successfully added to the returned array
     * with all parameters specified.
     * 
     * @template RealInstanceType of object (avoids type error of 'CustomCurl')
     */
    public function testStoreSellerListReturnsArrayOfMultiplePagesWithAllParamsSpecified() {

        // Arrange mock objects for the 'CustomCurl' class returning an XML string for two pages with 3 and 2 items
        $pageCounter = 0;
        $this->customCurl = $this->makeEmpty(CustomCurl::class, ['executeCurl' => function () use (&$pageCounter) {

            $pageCounter++;

            if ($pageCounter === 1) {
                return '<GetSellerListResponse xmlns="urn:ebay:apis:eBLBaseComponents">
                <Timestamp>2023-08-29T20:46:25.972Z</Timestamp>
                <Ack>Success</Ack>
                <Version>1271</Version>
                <Build>E1271_CORE_APISELLING_19187371_R1</Build>
                <HasMoreItems>true</HasMoreItems>
                <ItemArray>
                    <Item>
                        <ItemID>111111111111</ItemID>
                        <SellingStatus>
                            <HighBidder/>
                        </SellingStatus>
                        <ShippingDetails/>
                        <Title>ABC</Title>
                    </Item>
                    <Item>
                        <ItemID>222222222222</ItemID>
                        <SellingStatus>
                            <HighBidder/>
                        </SellingStatus>
                        <ShippingDetails/>
                        <Title>BCD</Title>
                    </Item>
                    <Item>
                        <ItemID>333333333333</ItemID>
                        <SellingStatus>
                            <HighBidder/>
                        </SellingStatus>
                        <ShippingDetails/>
                        <Title>CDE</Title>
                    </Item>
                </ItemArray>
                <PageNumber>1</PageNumber>
            </GetSellerListResponse>';
            }

            if ($pageCounter === 2) {
                return '<GetSellerListResponse xmlns="urn:ebay:apis:eBLBaseComponents">
                <Timestamp>2023-08-29T20:46:25.972Z</Timestamp>
                <Ack>Success</Ack>
                <Version>1271</Version>
                <Build>E1271_CORE_APISELLING_19187371_R1</Build>
                <HasMoreItems>false</HasMoreItems>
                <ItemArray>
                    <Item>
                        <ItemID>444444444444</ItemID>
                        <SellingStatus>
                            <HighBidder/>
                        </SellingStatus>
                        <ShippingDetails/>
                        <Title>DEF</Title>
                    </Item>
                    <Item>
                        <ItemID>555555555555</ItemID>
                        <SellingStatus>
                            <HighBidder/>
                        </SellingStatus>
                        <ShippingDetails/>
                        <Title>EFG</Title>
                    </Item>
                </ItemArray>
                <PageNumber>2</PageNumber>
            </GetSellerListResponse>';
            }
        }]);
        $this->initializeEbayApiService();

        // Act
        $result = $this->ebayApiService->storeSellerList(
            '2023-08-15T19:04:38.705Z',
            '2023-08-28T20:04:38.705Z',
            3,
            EbayGranularityLevel::COARSE,
            ['ItemID', 'Title', 'HasMoreItems', 'PageNumber']
        );

        // Assert that a non-empty array is returned with the expected number of elements and the expected content.
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertCount(5, $result);
        $this->assertArrayHasKey('111111111111', $result);
        $this->assertArrayHasKey('555555555555', $result);
    }

    /**
     * Tests the 'storeSellerList' method of the 'EbayApiService' class 
     * whether it throws an '\Exception' with the correct message when it cannot reach the API.
     */
    public function testStoreSellerListWithNoParamsThrowsExceptionOnCurlError() {

        // Arrange mock object for the 'CustomCurl' class to simulate a connection error
        $this->customCurl = $this->makeEmpty(CustomCurl::class, ['executeCurl' => function () {
            throw new \Exception('Error Message');  // Or whatever your customCurl's behavior is in case of a connection failure
        }]);
        $this->initializeEbayApiService();

        // Assert that an exception is thrown with corresponding error message reporting the cURL failure
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error Message');

        // Act
        $this->ebayApiService->storeSellerList();
    }

    /**
     * Tests the 'storeSellerList' method of the 'EbayApiService' class 
     * whether it throws an '\Exception' with the correct message when it cannot reach the API.
     */
    public function testStoreSellerListWithAllParamsThrowsExceptionOnCurlError() {

        // Arrange mock object for the 'CustomCurl' class to simulate a connection error
        $this->customCurl = $this->makeEmpty(CustomCurl::class, ['executeCurl' => function () {
            throw new \Exception('Error Message');  // Or whatever your customCurl's behavior is in case of a connection failure
        }]);
        $this->initializeEbayApiService();

        // Assert that an exception is thrown with corresponding error message reporting the cURL failure
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error Message');

        // Act
        $this->ebayApiService->storeSellerList(
            '2023-08-15T19:04:38.705Z',
            '2023-08-28T20:04:38.705Z',
            3,
            EbayGranularityLevel::COARSE,
            ['ItemID', 'Title', 'HasMoreItems', 'PageNumber']
        );
    }

    /**
     * Tests the `getItemDetails` method of the `EbayApiService` class 
     * whether it returns the XML string of the correct item
     * with mocked 'CustomLogger' and 'CustomCurl' classes. 
     * 
     * @template RealInstanceType of object (avoids type error of 'CustomCurl')
     */
    public function testGetItemDetailsReturnsAll() {

        // Arrange mock object for the 'CustomCurl' class returning a short XML string
        $itemId = 111111111111;
        $this->customCurl = $this->makeEmpty(CustomCurl::class, ['executeCurl' => function () use ($itemId) {
            return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <GetItemResponse xmlns=\"urn:ebay:apis:eBLBaseComponents\">
                    <Ack>Success</Ack>
                    <Item><ItemID>$itemId</ItemID></Item>
                </GetItemResponse>";
        }]);
        $this->initializeEbayApiService();

        // Act
        $result = $this->ebayApiService->getItemDetails($itemId);

        // Assert that the correct string is returned
        $this->assertIsString($result);
        $this->assertStringContainsString('<GetItemResponse', $result);
        $this->assertStringContainsString($itemId, $result);
        $this->assertStringContainsString('Success', $result);
    }

    /**
     * Tests the `getItemDetails` method of the `EbayApiService` class 
     * whether an exception is thrown after a cURL error of the API call
     * with mocked 'CustomLogger' and 'CustomCurl' classes. 
     * 
     * @template RealInstanceType of object (avoids type error of 'CustomCurl')
     */
    public function testGetItemDetailsThrowsExceptionOnCurlError() {

        // Arrange mock objects for the 'CustomCurl' class throwing an Exception
        $itemId = 111111111111;

        $this->customCurl = $this->make(CustomCurl::class, ['executeCurl' => function () {
            throw new \Exception('Error Message');
        }]);
        $this->initializeEbayApiService();

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error Message');

        // Act
        $this->ebayApiService->getItemDetails($itemId);
    }

    /**
     * Tests the `getSellerEvents` method of the `EbayApiService` class 
     * wether it returns the XML string of the correct seller events
     * with mocked 'CustomLogger', 'CustomCurl' and 'XmlUtils' classes. 
     */
    public function testGetSellerEventsReturnsCorrectData() {

        // Arrange mock object for the 'CustomCurl' class returning a short XML string
        $modTimeFrom = '2023-08-28T10:00:00Z';
        $this->customCurl = $this->makeEmpty(CustomCurl::class, ['executeCurl' => function () {
            return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <GetSellerEventsResponse xmlns=\"urn:ebay:apis:eBLBaseComponents\">
                <Ack>Success</Ack>
                <Item><ItemID>111111111111</ItemID><SellingStatus><ListingStatus>Active</ListingStatus></SellingStatus></Item>
            </GetSellerEventsResponse>";
        }]);
        $this->initializeEbayApiService();

        // Act
        $result = $this->ebayApiService->getSellerEvents($modTimeFrom);

        // Assert that the correct string is returned
        $this->assertIsString($result);
        $this->assertStringContainsString('<GetSellerEventsResponse', $result);
        $this->assertStringContainsString('ItemID', $result);
        $this->assertStringContainsString('ListingStatus', $result);
    }

    /**
     * Tests the `getSellerEvents` method of the `EbayApiService` class 
     * wether an exception is thrown if there is a cURL error.
     */
    public function testGetSellerEventsThrowsExceptionOnCurlError() {

        // Arrange mock object for the 'CustomCurl' class throwing an Exception
        $modTimeFrom = '2023-08-28T10:00:00Z';
        $this->customCurl = $this->make(CustomCurl::class, ['executeCurl' => function () {
            throw new \Exception('Error Message');
        }]);
        $this->initializeEbayApiService();

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Failed 'GetSellerEvents': Error Message");

        // Act
        $this->ebayApiService->getSellerEvents($modTimeFrom);
    }
}

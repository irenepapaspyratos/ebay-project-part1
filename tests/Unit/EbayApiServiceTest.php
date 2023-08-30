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
    private $initialActiveIdsPath;
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

        // Define the temporary paths for writable test files
        $this->initialActiveIdsPath = codecept_data_dir('initial_active_ids.xml');

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
            throw new \Exception('cURL ERROR:');
        }]);
        $this->ebayApiService = new EbayApiService($this->xmlUtils, $this->customLogger, $this->customCurl, $this->dateUtils, 9, '1', 1,);

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Failed 'GeteBayOfficalTime': cURL ERROR");

        // Act
        $this->ebayApiService->getTimestamp();
    }

    /**
     * Tests the 'storeSellerList' method of the 'EbayApiService' class 
     * whether the XML response is successfully written to the correct file
     * with no other parameters specified.
     * 
     * @template RealInstanceType of object (avoids type error of 'CustomCurl')
     */
    public function testStoreSellerListWritesToCorrectFileWithNoParameterSpecified() {

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
                            <ItemID>11111111111</ItemID>
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
        $result = $this->ebayApiService->storeSellerList($this->initialActiveIdsPath);
        $expectPath = $this->initialActiveIdsPath;
        $expectContent = file_get_contents($expectPath);

        // Assert that true is returned and the expected file exists, is not empty and contains the correct content
        $this->assertTrue($result);
        $this->assertNotEmpty($expectPath);
        $this->assertStringContainsString('<GetSellerListResponse ', $expectContent);
        $this->assertStringContainsString('<ItemArray>', $expectContent);
    }

    /**
     * Tests the 'storeSellerList' method of the 'EbayApiService' class 
     * whether the XML response of only one page is successfully written to the correct file
     * with all parameters specified.
     * 
     * @template RealInstanceType of object (avoids type error of 'CustomCurl')
     */
    public function testStoreSellerListWritesOnePageToCorrectFileWithAllParamsSpecified() {

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
            $this->initialActiveIdsPath,
            '2023-08-28T19:04:38.705Z',
            '2023-08-28T20:04:38.705Z',
            50,
            EbayGranularityLevel::COARSE,
            ['ItemID', 'Title', 'HasMoreItems', 'PageNumber']
        );

        $expectPath = str_replace('.xml', '001.xml', $this->initialActiveIdsPath);
        $expectContent = file_get_contents($expectPath);

        // Assert that true is returned and only the expected file was created, is not empty and contains the correct content
        $this->assertTrue($result);
        $this->assertNotEmpty($expectPath);
        $this->assertFileNotExists(str_replace('.xml', '002.xml', $this->initialActiveIdsPath));
        $this->assertStringContainsString('<GetSellerListResponse ', $expectContent);
        $this->assertStringContainsString('<ItemArray>', $expectContent);
        $this->assertStringContainsString('<Title>', $expectContent);
        $this->assertStringContainsString('<HasMoreItems>false</HasMoreItems>', $expectContent);
    }

    /**
     * Tests the 'storeSellerList' method of the 'EbayApiService' class 
     * whether the XML responses of all pages are successfully written to the correct files
     * with all parameters specified.
     * 
     * @template RealInstanceType of object (avoids type error of 'CustomCurl')
     */
    public function testStoreSellerListWritesAllPagesToCorrectFilesWithAllParamsSpecified() {

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
            $this->initialActiveIdsPath,
            '2023-08-15T19:04:38.705Z',
            '2023-08-28T20:04:38.705Z',
            3,
            EbayGranularityLevel::COARSE,
            ['ItemID', 'Title', 'HasMoreItems', 'PageNumber']
        );

        $expectPath1 = str_replace('.xml', '001.xml', $this->initialActiveIdsPath);
        $expectPath2 = str_replace('.xml', '002.xml', $this->initialActiveIdsPath);
        $expectContent1 = file_get_contents($expectPath1);
        $expectContent2 = file_get_contents($expectPath2);
        $countItemResult1 = substr_count($expectContent1, '<Item>');
        $countItemResult2 = substr_count($expectContent2, '<Item>');
        $countTitleResult1 = substr_count($expectContent1, '<Item>');
        $countTitleResult2 = substr_count($expectContent2, '<Item>');

        // Assert that true is returned, the expected file for the first page exists, contains the right content and <Item> as well as <Title> appears three times.
        $this->assertTrue($result);

        $this->assertFileExists($expectPath1);
        $this->assertStringContainsString('<GetSellerListResponse ', $expectContent1);
        $this->assertStringContainsString('<HasMoreItems>true</HasMoreItems>', $expectContent1);
        $this->assertEquals(3, $countItemResult1);
        $this->assertEquals(3, $countTitleResult1);

        // Assert that the expected file for the second page exists and contains the right content and <Item>  as well as <Title> appears only once.
        $this->assertFileExists($expectPath2);
        $this->assertStringContainsString('<GetSellerListResponse ', $expectContent2);
        $this->assertStringContainsString('<HasMoreItems>false</HasMoreItems>', $expectContent2);
        $this->assertEquals(2, $countItemResult2);
        $this->assertEquals(2, $countTitleResult2);
    }

    /**
     * Tests the 'storeSellerList' method of the 'EbayApiService' class 
     * wether it handles very large XML responses correctly.
     * 
     * @template RealInstanceType of object (avoids type error of 'CustomCurl')
     */
    public function testStoreSellerListHandlesLargeResponses() {

        // Arrange mock object for the 'CustomCurl' class returning a very large XML string
        $number = 100000;
        $largeXML = '<ItemArray>' . str_repeat('<Item><ItemID>111111111111</ItemID></Item>', $number) . '</ItemArray>';
        $this->customCurl = $this->makeEmpty(CustomCurl::class, ['executeCurl' => function () use ($largeXML) {
            return $largeXML;
        }]);
        $this->initializeEbayApiService();

        // Act
        $result = $this->ebayApiService->storeSellerList($this->initialActiveIdsPath);

        $expectPath = $this->initialActiveIdsPath;
        $expectContent = file_get_contents($expectPath);
        $countItemResult = substr_count($expectContent, '<Item>');

        // Assert that true is returned, the expected file exists and <Item> appears 100000 times.
        $this->assertTrue($result);
        $this->assertFileExists($expectPath);
        $this->assertEquals($number, $countItemResult);
    }

    /**
     * Tests the 'storeSellerList' method of the 'EbayApiService' class 
     * whether it throws an '\Exception' with the correct message when it cannot write to a log file.
     */
    public function testStoreSellerListCannotWriteToFile() {

        // Arrange mock object for the 'CustomCurl' class returning a simple XML string
        $this->customCurl = $this->makeEmpty(CustomCurl::class, ['executeCurl' => function () {
            return '<Item><ItemID>111111111111</ItemID></Item>';
        }]);
        $this->initializeEbayApiService();

        // Assert that an exception is thrown with corresponding error message reporting the non writable file
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Failed 'GetSellerList': ");

        // Act
        $this->ebayApiService->storeSellerList($this->nonWritablePath);
    }

    /**
     * Tests the 'storeSellerList' method of the 'EbayApiService' class 
     * whether it throws an '\Exception' with the correct message when it cannot reach the API.
     */
    public function testStoreSellerListNoConnectionWithNoParameterSpecified() {

        // Arrange mock object for the 'CustomCurl' class to simulate a connection error
        $this->customCurl = $this->makeEmpty(CustomCurl::class, ['executeCurl' => function () {
            throw new \Exception("No connection.");  // Or whatever your customCurl's behavior is in case of a connection failure
        }]);
        $this->initializeEbayApiService();

        // Assert that an exception is thrown with corresponding error message reporting the cURL failure
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("No connection.");

        // Act
        $this->ebayApiService->storeSellerList($this->initialActiveIdsPath);
    }

    /**
     * Tests the 'storeSellerList' method of the 'EbayApiService' class 
     * whether it throws an '\Exception' with the correct message when it cannot reach the API.
     */
    public function testStoreSellerListNoConnectionWithAllParametersSpecified() {

        // Arrange mock object for the 'CustomCurl' class to simulate a connection error
        $this->customCurl = $this->makeEmpty(CustomCurl::class, ['executeCurl' => function () {
            throw new \Exception("No connection.");  // Or whatever your customCurl's behavior is in case of a connection failure
        }]);
        $this->initializeEbayApiService();

        // Assert that an exception is thrown with corresponding error message reporting the cURL failure
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("No connection.");

        // Act
        $this->ebayApiService->storeSellerList(
            $this->initialActiveIdsPath,
            '2023-08-15T19:04:38.705Z',
            '2023-08-28T20:04:38.705Z',
            3,
            EbayGranularityLevel::COARSE,
            ['ItemID', 'Title', 'HasMoreItems', 'PageNumber']
        );
    }
}

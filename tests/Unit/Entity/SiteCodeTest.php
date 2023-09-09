<?php

namespace Tests\Unit\Entity;

use App\Entity\SiteCode;
use Codeception\Test\Unit;

/**
 * The 'SiteCodeTest' is a unit test class for testing the 'SiteCode' class.
 * 
 * Tests the functionality of creating an instance and getting/setting the class-related properties.
 */
class SiteCodeTest extends Unit {

    protected $tester;
    private $keyArray;
    private $siteCode;
    private $siteCodeWithout;

    /**
     * Sets up the necessary environment for running tests by 
     * creating an array of keys and different instances of 'SiteCode'.
     */
    protected function _before() {

        $this->keyArray = ['id', 'site_id', 'site_name', 'site_global_id'];

        $this->siteCode = new SiteCode($this->keyArray, 77, 'eBay Germany', 'EBAY-DE', 2);
        $this->siteCodeWithout = new SiteCode($this->keyArray, 0, 'eBay United States', 'EBAY-US');
    }

    /**
     * Tests whether the 'SiteCode' instance is created correctly with and without id.
     */
    public function testSiteCodeCreation() {

        // Assert that an instance of 'SiteCode' was created
        $this->assertInstanceOf(SiteCode::class, $this->siteCode);
        $this->assertInstanceOf(SiteCode::class, $this->siteCodeWithout);
    }

    /**
     * Tests whether the getters of the 'SiteCode' class return the correct values with and without id.
     */
    public function testSiteCodeGetters() {

        // Assert that the getters return the expected value
        $this->assertEquals(2, $this->siteCode->getId());
        $this->assertEquals(77, $this->siteCode->getSiteId());
        $this->assertEquals('eBay Germany', $this->siteCode->getSiteName());
        $this->assertEquals('EBAY-DE', $this->siteCode->getSiteGlobalId());

        $this->assertEquals(null, $this->siteCodeWithout->getId());
        $this->assertEquals(0, $this->siteCodeWithout->getSiteId());
        $this->assertEquals('eBay United States', $this->siteCodeWithout->getSiteName());
        $this->assertEquals('EBAY-US', $this->siteCodeWithout->getSiteGlobalId());
    }

    /**
     * Tests whether the setters in the 'SiteCode' class modify the properties correctly.
     */
    public function testSiteCodeSetters() {

        // Act 
        $this->siteCode->setId(1);
        $this->siteCode->setSiteId(0);
        $this->siteCode->setSiteName('eBay United States');
        $this->siteCode->setSiteGlobalId('EBAY-US');

        // Assert that the correct values of the changes are returned
        $this->assertEquals(1, $this->siteCode->getId());
        $this->assertEquals(0, $this->siteCode->getSiteId());
        $this->assertEquals('eBay United States', $this->siteCode->getSiteName());
        $this->assertEquals('EBAY-US', $this->siteCode->getSiteGlobalId());
    }

    /**
     * Tests the 'toArray' method of the 'SiteCode' class whether
     * it converts a SiteCode object to the correct array with and without id.
     */
    public function testSiteCodeToArrayConversion() {

        $expectedArray = [
            'id' => 2,
            'site_id' => 77,
            'site_name' => 'eBay Germany',
            'site_global_id' => 'EBAY-DE'
        ];

        $expectedArrayWithout = [
            'id' => null,
            'site_id' => 0,
            'site_name' => 'eBay United States',
            'site_global_id' => 'EBAY-US'
        ];


        // Assert
        $this->assertEquals($expectedArray, $this->siteCode->toArray());
        $this->assertEquals($expectedArrayWithout, $this->siteCodeWithout->toArray());
    }

    /**
     * Tests the 'toArray' method of the 'SiteCode' class whether
     * an exception is thrown when trying to convert an object to an array
     * with an invalid key.
     */
    public function testSiteCodeToArrayThrowsExceptionForInvalidKey() {

        // Arrange: Add an invalid key to the keyArray
        $invalidKeyArray = ['id', 'side_id', 'site_name', 'site_global_id'];
        $siteCodeWithInvalidKey = new SiteCode($invalidKeyArray, '88', 'eBay Spain', 'EBAY-ES');

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid Request: Getter for 'side_id' does not exist in App\Entity\SiteCode.");

        // Act
        $siteCodeWithInvalidKey->toArray();
    }
}

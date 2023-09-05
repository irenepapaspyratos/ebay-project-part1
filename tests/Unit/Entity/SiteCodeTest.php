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

    /**
     * Tests whether the 'SiteCode' instance is created correctly without id.
     */
    public function testSiteCodeCreationWithoutId() {

        // Act
        $siteCode = new SiteCode(null, 77, 'eBay Germany', 'EBAY-DE');

        // Assert that an instance of 'SiteCode' was created
        $this->assertInstanceOf(SiteCode::class, $siteCode);
    }

    /**
     * Tests whether the 'SiteCode' instance is created correctly with id.
     */
    public function testSiteCodeCreationWithId() {

        // Act
        $siteCode = new SiteCode(2, 77, 'eBay Germany', 'EBAY-DE');

        // Assert that an instance of 'SiteCode' was created
        $this->assertInstanceOf(SiteCode::class, $siteCode);
    }

    /**
     * Tests whether the getters of the 'SiteCode' class return the correct values without id.
     */
    public function testSiteCodeGettersWithoutId() {

        // Act
        $siteCode = new SiteCode(null, 77, 'eBay Germany', 'EBAY-DE');

        // Assert that the getters return the expected value
        $this->assertEquals(null, $siteCode->getId());
        $this->assertEquals(77, $siteCode->getSiteId());
        $this->assertEquals('eBay Germany', $siteCode->getSiteName());
        $this->assertEquals('EBAY-DE', $siteCode->getGlobalId());
    }

    /**
     * Tests whether the getters of the 'SiteCode' class return the correct values with id.
     */
    public function testSiteCodeGettersWithId() {

        // Act
        $siteCode = new SiteCode(2, 77, 'eBay Germany', 'EBAY-DE');

        // Assert that the getters return the expected value
        $this->assertEquals(2, $siteCode->getId());
        $this->assertEquals(77, $siteCode->getSiteId());
        $this->assertEquals('eBay Germany', $siteCode->getSiteName());
        $this->assertEquals('EBAY-DE', $siteCode->getGlobalId());
    }

    /**
     * Tests whether the setters in the 'SiteCode' class modify the properties correctly.
     */
    public function testSiteCodeSettersModifyProperties() {

        // Arrange
        $siteCode = new SiteCode(2, 77, 'eBay Germany', 'EBAY-DE');

        // Act 
        $siteCode->setId(1);
        $siteCode->setSiteId(0);
        $siteCode->setSiteName('eBay United States');
        $siteCode->setGlobalId('EBAY-US');

        // Assert that the correct values of the changes are returned
        $this->assertEquals(1, $siteCode->getId());
        $this->assertEquals(0, $siteCode->getSiteId());
        $this->assertEquals('eBay United States', $siteCode->getSiteName());
        $this->assertEquals('EBAY-US', $siteCode->getGlobalId());
    }

    /**
     * Tests the 'toArray' method of the 'SiteCode' class whether
     * it converts a SiteCode object to the correct array without id.
     */
    public function testToArrayConversionWithoutId() {

        // Act
        $siteCode = new SiteCode(null, 77, 'eBay Germany', 'EBAY-DE');

        $expectedArray = [
            'id' => null,
            'site_id' => 77,
            'site_name' => 'eBay Germany',
            'global_id' => 'EBAY-DE'
        ];

        // Assert
        $this->assertEquals($expectedArray, $siteCode->toArray());
    }

    /**
     * Tests the 'toArray' method of the 'SiteCode' class whether
     * it converts a SiteCode object to the correct array with id.
     */
    public function testToArrayConversionWithId() {

        // Act
        $siteCode = new SiteCode(1, 0, 'eBay United States', 'EBAY-US');

        $expectedArray = [
            'id' => 1,
            'site_id' => 0,
            'site_name' => 'eBay United States',
            'global_id' => 'EBAY-US'
        ];

        // Assert
        $this->assertEquals($expectedArray, $siteCode->toArray());
    }
}

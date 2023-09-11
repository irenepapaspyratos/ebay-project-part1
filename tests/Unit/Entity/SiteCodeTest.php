<?php

namespace Tests\Unit\Entity;

use App\Entity\SiteCode;
use Codeception\Test\Unit;

/**
 * The 'SiteCodeTest' is a unit test class for testing the 'SiteCode' entity.
 * 
 * Tests the functionality of creating an instance and getting/setting the entity-related properties.
 */
class SiteCodeTest extends Unit {

    protected $tester;
    private $siteCode;
    private $prefix = 'ebay_';

    /**
     * Sets up the necessary environment for running tests by 
     * creating an instance of 'SiteCode'.
     */
    protected function _before() {

        $this->siteCode = new SiteCode($this->prefix);
    }

    /**
     * Tests whether the 'SiteCode' instance is created correctly.
     */
    public function testSiteCodeCreation() {

        // Assert that an instance of 'SiteCode' was created
        $this->assertInstanceOf(SiteCode::class, $this->siteCode);
    }

    /**
     * Tests whether the setters in the 'SiteCode' entity modify the properties correctly.
     */
    public function testSiteCodeSettersAndGetters() {

        // Act 
        $this->siteCode->id = 5;
        $this->siteCode->site_id = 200;
        $this->siteCode->site_name = 'eBay US';
        $this->siteCode->site_global_id = 'EBAY-US';

        // Assert that the correct values of the changes are returned
        $this->assertEquals(5, $this->siteCode->id);
        $this->assertEquals(200, $this->siteCode->site_id);
        $this->assertEquals('eBay US', $this->siteCode->site_name);
        $this->assertEquals('EBAY-US', $this->siteCode->site_global_id);
    }

    /**
     * Tests the 'toArray' method of the 'SiteCode' entity whether
     * it converts a SiteCode object to the correct array.
     */
    public function testSiteCodeToArrayConversion() {

        // Act
        $this->siteCode->id = 1;
        $this->siteCode->site_id = 100;
        $this->siteCode->site_name = 'eBay UK';
        $this->siteCode->site_global_id = 'EBAY-UK';

        $expectedArray = [
            'id' => 1,
            'site_id' => 100,
            'site_name' => 'eBay UK',
            'site_global_id' => 'EBAY-UK',
        ];

        // Assert that the correct array is returned
        $this->assertEquals($expectedArray, $this->siteCode->toArray());
    }

    /**
     * Tests the getter of the 'SiteCode' class whether
     * it throws an exception on a missing table key.
     */
    public function testSiteCodeMissingTableKeyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Columns for table 'wrongprefix_site_code' not found.");

        // Act
        $siteCode = new SiteCode('wrongprefix_');
    }

    /**
     * Tests the setter of the 'SiteCode' entity whether
     * it throws an exception on an invalid property.
     */
    public function testSiteCodeSetterInvalidPropertyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid property: invalid_property");

        // Act
        $this->siteCode->invalid_property = 'test';
    }

    /**
     * Tests the setter of the 'SiteCode' entity whether
     * it throws an exception on an invalid property type.
     */
    public function testSiteCodeSetterInvalidTypeException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid type for 'site_id'. Expected integer, got string");

        // Act
        $this->siteCode->site_id = '300';
    }

    /**
     * Tests the getter of the 'SiteCode' entity whether
     * it throws an exception on an invalid property.
     */
    public function testSiteCodeGetterInvalidPropertyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Property 'invalid_property' not existing");

        // Act
        $value = $this->siteCode->invalid_property;
    }
}

<?php

namespace Tests\Unit\Entity;

use App\Entity\SiteDetail;
use Codeception\Test\Unit;

/**
 * The 'SiteDetailTest' is a unit test class for testing the 'SiteDetail' entity.
 * 
 * Tests the functionality of creating an instance and getting/setting the entity-related properties.
 */
class SiteDetailTest extends Unit {

    protected $tester;
    private $siteDetail;
    private $table = 'ebay_site_detail';
    private $tables = [
        'ebay_site_detail' => [
            'columns' => [
                'id' => 'integer',
                'site_id' => 'integer',
                'site_name' => 'string',
            ],
        ],
    ];

    /**
     * Sets up the necessary environment for running tests by 
     * creating an instance of 'SiteDetail'.
     */
    protected function _before() {

        $this->siteDetail = new SiteDetail($this->table, $this->tables);
    }

    /**
     * Tests whether the 'SiteDetail' instance is created correctly.
     */
    public function testSiteDetailCreation() {

        // Assert that an instance of 'SiteDetail' was created
        $this->assertInstanceOf(SiteDetail::class, $this->siteDetail);
    }

    /**
     * Tests whether the setters in the 'SiteDetail' entity modify the properties correctly.
     */
    public function testSiteDetailSettersAndGetters() {

        // Act 
        $this->siteDetail->id = 5;
        $this->siteDetail->site_id = 200;
        $this->siteDetail->site_name = 'eBay US';

        // Assert that the correct values of the changes are returned
        $this->assertTrue(is_int($this->siteDetail->id));
        $this->assertEquals(5, $this->siteDetail->id);
        $this->assertEquals(200, $this->siteDetail->site_id);
        $this->assertEquals('eBay US', $this->siteDetail->site_name);
    }

    /**
     * Tests the 'toArray' method of the 'SiteDetail' entity whether
     * it converts a SiteDetail object to the correct array.
     */
    public function testSiteDetailToArrayConversion() {

        // Act
        $this->siteDetail->id = 1;
        $this->siteDetail->site_id = 100;
        $this->siteDetail->site_name = 'eBay UK';

        $expectedArray = [
            'id' => 1,
            'site_id' => 100,
            'site_name' => 'eBay UK',
        ];

        // Assert that the correct array is returned
        $this->assertEquals($expectedArray, $this->siteDetail->toArray());
    }

    /**
     * Tests the getter of the 'SiteDetail' class whether
     * it throws an exception on a missing table key.
     */
    public function testSiteDetailMissingTableKeyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Columns for table 'wrongtable' not found.");

        // Act
        $siteDetail = new SiteDetail('wrongtable', $this->tables);
    }

    /**
     * Tests the setter of the 'SiteDetail' entity whether
     * it throws an exception on an invalid property.
     */
    public function testSiteDetailSetterInvalidPropertyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid property: invalid_property");

        // Act
        $this->siteDetail->invalid_property = 'test';
    }

    /**
     * Tests the setter of the 'SiteDetail' entity whether
     * it throws an exception on an invalid property type.
     */
    public function testSiteDetailSetterInvalidTypeException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid type for 'site_id'. Expected integer, got string");

        // Act
        $this->siteDetail->site_id = '300';
    }

    /**
     * Tests the getter of the 'SiteDetail' entity whether
     * it throws an exception on an invalid property.
     */
    public function testSiteDetailGetterInvalidPropertyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Property 'invalid_property' not existing");

        // Act
        $value = $this->siteDetail->invalid_property;
    }
}

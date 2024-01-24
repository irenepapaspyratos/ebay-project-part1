<?php

namespace Tests\Unit\Entity;

use App\Entity\CountryDetail;
use Codeception\Test\Unit;

/**
 * The 'CountryDetailTest' is a unit test class for testing the 'CountryDetail' class.
 * 
 * Tests the functionality of creating an instance and getting/setting the class-related properties.
 */
class CountryDetailTest extends Unit {

    protected $tester;
    private $countryDetail;
    private $table = 'ebay_country_detail';
    private $tables = [
        'ebay_country_detail' => [
            'columns' => [
                'id' => 'integer',
                'country_code' => 'string',
                'country_description' => 'string',
            ],
        ],
    ];

    /**
     * Sets up the necessary environment for running tests by 
     * creating an instance of 'CountryDetail'.
     */
    protected function _before() {

        $this->countryDetail = new CountryDetail($this->table, $this->tables);
    }

    /**
     * Tests whether the 'CountryDetail' instance is created correctly.
     */
    public function testCountryDetailCreation() {

        // Assert that an instance of 'CountryDetail' was created
        $this->assertInstanceOf(CountryDetail::class, $this->countryDetail);
    }

    /**
     * Tests whether the setters in the 'CountryDetail' class modify the properties correctly.
     */
    public function testCountryDetailSettersAndGetters() {

        // Act 
        $this->countryDetail->id = 5;
        $this->countryDetail->country_code = 'US';
        $this->countryDetail->country_description = 'United States';

        // Assert that the correct values of the changes are returned
        $this->assertTrue(is_int($this->countryDetail->id));
        $this->assertEquals(5, $this->countryDetail->id);
        $this->assertEquals('US', $this->countryDetail->country_code);
        $this->assertEquals('United States', $this->countryDetail->country_description);
    }

    /**
     * Tests the 'toArray' method of the 'CountryDetail' class whether
     * it converts a CountryDetail object to the correct array.
     */
    public function testCountryDetailToArrayConversion() {

        // Act
        $this->countryDetail->id = 1;
        $this->countryDetail->country_code = 'CA';
        $this->countryDetail->country_description = 'Canada';

        $expectedArray = [
            'id' => 1,
            'country_code' => 'CA',
            'country_description' => 'Canada',
        ];

        // Assert that the correct array is returned
        $this->assertEquals($expectedArray, $this->countryDetail->toArray());
    }

    /**
     * Tests the getter of the 'CountryDetail' class whether
     * it throws an exception on a missing table key.
     */
    public function testCountryDetailMissingTableKeyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Columns for table 'wrongtable' not found.");

        // Act
        $countryDetail = new CountryDetail('wrongtable', $this->tables);
    }

    /**
     * Tests the setter of the 'CountryDetail' class whether
     * it throws an exception on an invalid property.
     */
    public function testCountryDetailSetterInvalidPropertyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid property: invalid_property");

        // Act
        $this->countryDetail->invalid_property = 'test';
    }

    /**
     * Tests the setter of the 'CountryDetail' class whether
     * it throws an exception on an invalid property type.
     */
    public function testCountryDetailSetterInvalidTypeException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid type for 'country_code'. Expected string, got integer");

        // Act
        $this->countryDetail->country_code = 5;
    }

    /**
     * Tests the getter of the 'CountryDetail' class whether
     * it throws an exception on an invalid property.
     */
    public function testCountryDetailGetterInvalidPropertyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Property 'invalid_property' not existing");

        // Act
        $value = $this->countryDetail->invalid_property;
    }
}

<?php

namespace Tests\Unit\Entity;

use App\Entity\CountryCode;
use Codeception\Test\Unit;

/**
 * The 'CountryCodeTest' is a unit test class for testing the 'CountryCode' class.
 * 
 * Tests the functionality of creating an instance and getting/setting the class-related properties.
 */
class CountryCodeTest extends Unit {

    protected $tester;
    private $countryCode;
    private $prefix = 'ebay_';

    /**
     * Sets up the necessary environment for running tests by 
     * creating an instance of 'CountryCode'.
     */
    protected function _before() {

        $this->countryCode = new CountryCode($this->prefix);
    }

    /**
     * Tests whether the 'CountryCode' instance is created correctly.
     */
    public function testCountryCodeCreation() {

        // Assert that an instance of 'CountryCode' was created
        $this->assertInstanceOf(CountryCode::class, $this->countryCode);
    }

    /**
     * Tests whether the setters in the 'CountryCode' class modify the properties correctly.
     */
    public function testCountryCodeSettersAndGetters() {

        // Act 
        $this->countryCode->id = 5;
        $this->countryCode->country_code = 'US';
        $this->countryCode->country_description = 'United States';
        $this->countryCode->fk_default_currency = 1;

        // Assert that the correct values of the changes are returned
        $this->assertEquals(5, $this->countryCode->id);
        $this->assertEquals('US', $this->countryCode->country_code);
        $this->assertEquals('United States', $this->countryCode->country_description);
        $this->assertEquals(1, $this->countryCode->fk_default_currency);
    }

    /**
     * Tests the 'toArray' method of the 'CountryCode' class whether
     * it converts a CountryCode object to the correct array.
     */
    public function testCountryCodeToArrayConversion() {

        // Act
        $this->countryCode->id = 1;
        $this->countryCode->country_code = 'CA';
        $this->countryCode->country_description = 'Canada';
        $this->countryCode->fk_default_currency = 2;

        $expectedArray = [
            'id' => 1,
            'country_code' => 'CA',
            'country_description' => 'Canada',
            'fk_default_currency' => 2
        ];

        // Assert that the correct array is returned
        $this->assertEquals($expectedArray, $this->countryCode->toArray());
    }

    /**
     * Tests the getter of the 'CountryCode' class whether
     * it throws an exception on a missing table key.
     */
    public function testCountryCodeMissingTableKeyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Columns for table 'wrongprefix_country_code' not found.");

        // Act
        $countryCode = new CountryCode('wrongprefix_');
    }

    /**
     * Tests the setter of the 'CountryCode' class whether
     * it throws an exception on an invalid property.
     */
    public function testCountryCodeSetterInvalidPropertyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid property: invalid_property");

        // Act
        $this->countryCode->invalid_property = 'test';
    }

    /**
     * Tests the setter of the 'CountryCode' class whether
     * it throws an exception on an invalid property type.
     */
    public function testCountryCodeSetterInvalidTypeException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid type for 'country_code'. Expected string, got integer");

        // Act
        $this->countryCode->country_code = 5;
    }

    /**
     * Tests the getter of the 'CountryCode' class whether
     * it throws an exception on an invalid property.
     */
    public function testCountryCodeGetterInvalidPropertyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Property 'invalid_property' not existing");

        // Act
        $value = $this->countryCode->invalid_property;
    }
}

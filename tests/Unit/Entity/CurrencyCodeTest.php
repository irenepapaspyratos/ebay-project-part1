<?php

namespace Tests\Unit\Entity;

use App\Entity\CurrencyCode;
use Codeception\Test\Unit;

/**
 * The 'CurrencyCodeTest' is a unit test class for testing the 'CurrencyCode' entity.
 * 
 * Tests the functionality of creating an instance and getting/setting the entity-related properties.
 */
class CurrencyCodeTest extends Unit {

    protected $tester;
    private $currencyCode;
    private $prefix = 'ebay_';

    /**
     * Sets up the necessary environment for running tests by 
     * creating an instance of 'CurrencyCode'.
     */
    protected function _before() {

        $this->currencyCode = new CurrencyCode($this->prefix);
    }

    /**
     * Tests whether the 'CurrencyCode' instance is created correctly.
     */
    public function testCurrencyCodeCreation() {

        // Assert that an instance of 'CurrencyCode' was created
        $this->assertInstanceOf(CurrencyCode::class, $this->currencyCode);
    }

    /**
     * Tests whether the setters in the 'CurrencyCode' entity modify the properties correctly.
     */
    public function testCurrencyCodeSettersAndGetters() {

        // Act 
        $this->currencyCode->id = 5;
        $this->currencyCode->currency_code = 'USD';
        $this->currencyCode->currency_description = 'US Dollar';

        // Assert that the correct values of the changes are returned
        $this->assertEquals(5, $this->currencyCode->id);
        $this->assertEquals('USD', $this->currencyCode->currency_code);
        $this->assertEquals('US Dollar', $this->currencyCode->currency_description);
    }

    /**
     * Tests the 'toArray' method of the 'CurrencyCode' entity whether
     * it converts a CurrencyCode object to the correct array.
     */
    public function testCurrencyCodeToArrayConversion() {

        // Act
        $this->currencyCode->id = 1;
        $this->currencyCode->currency_code = 'EUR';
        $this->currencyCode->currency_description = 'Euro';

        $expectedArray = [
            'id' => 1,
            'currency_code' => 'EUR',
            'currency_description' => 'Euro',
        ];

        // Assert that the correct array is returned
        $this->assertEquals($expectedArray, $this->currencyCode->toArray());
    }

    /**
     * Tests the getter of the 'CurrencyCode' class whether
     * it throws an exception on a missing table key.
     */
    public function testCurrencyCodeMissingTableKeyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Columns for table 'wrongprefix_currency_code' not found.");

        // Act
        $currencyCode = new CurrencyCode('wrongprefix_');
    }

    /**
     * Tests the setter of the 'CurrencyCode' entity whether
     * it throws an exception on an invalid property.
     */
    public function testCurrencyCodeSetterInvalidPropertyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid property: invalid_property");

        // Act
        $this->currencyCode->invalid_property = 'test';
    }

    /**
     * Tests the setter of the 'CurrencyCode' entity whether
     * it throws an exception on an invalid property type.
     */
    public function testCurrencyCodeSetterInvalidTypeException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid type for 'currency_code'. Expected string, got integer");

        // Act
        $this->currencyCode->currency_code = 5;
    }

    /**
     * Tests the getter of the 'CurrencyCode' entity whether
     * it throws an exception on an invalid property.
     */
    public function testCurrencyCodeGetterInvalidPropertyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Property 'invalid_property' not existing");

        // Act
        $value = $this->currencyCode->invalid_property;
    }
}

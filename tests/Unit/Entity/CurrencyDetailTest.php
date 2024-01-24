<?php

namespace Tests\Unit\Entity;

use App\Entity\CurrencyDetail;
use Codeception\Test\Unit;

/**
 * The 'CurrencyDetailTest' is a unit test class for testing the 'CurrencyDetail' entity.
 * 
 * Tests the functionality of creating an instance and getting/setting the entity-related properties.
 */
class CurrencyDetailTest extends Unit {

    protected $tester;
    private $currencyDetail;
    private $table = 'ebay_currency_detail';
    private $tables = [
        'ebay_currency_detail' => [
            'columns' => [
                'id' => 'integer',
                'currency_code' => 'string',
                'currency_description' => 'string',
            ],
        ],
    ];

    /**
     * Sets up the necessary environment for running tests by 
     * creating an instance of 'CurrencyDetail'.
     */
    protected function _before() {

        $this->currencyDetail = new CurrencyDetail($this->table, $this->tables);
    }

    /**
     * Tests whether the 'CurrencyDetail' instance is created correctly.
     */
    public function testCurrencyDetailCreation() {

        // Assert that an instance of 'CurrencyDetail' was created
        $this->assertInstanceOf(CurrencyDetail::class, $this->currencyDetail);
    }

    /**
     * Tests whether the setters in the 'CurrencyDetail' entity modify the properties correctly.
     */
    public function testCurrencyDetailSettersAndGetters() {

        // Act 
        $this->currencyDetail->id = 5;
        $this->currencyDetail->currency_code = 'USD';
        $this->currencyDetail->currency_description = 'US Dollar';

        // Assert that the correct values of the changes are returned
        $this->assertTrue(is_int($this->currencyDetail->id));
        $this->assertEquals(5, $this->currencyDetail->id);
        $this->assertEquals('USD', $this->currencyDetail->currency_code);
        $this->assertEquals('US Dollar', $this->currencyDetail->currency_description);
    }

    /**
     * Tests the 'toArray' method of the 'CurrencyDetail' entity whether
     * it converts a CurrencyDetail object to the correct array.
     */
    public function testCurrencyDetailToArrayConversion() {

        // Act
        $this->currencyDetail->id = 1;
        $this->currencyDetail->currency_code = 'EUR';
        $this->currencyDetail->currency_description = 'Euro';

        $expectedArray = [
            'id' => 1,
            'currency_code' => 'EUR',
            'currency_description' => 'Euro',
        ];

        // Assert that the correct array is returned
        $this->assertEquals($expectedArray, $this->currencyDetail->toArray());
    }

    /**
     * Tests the getter of the 'CurrencyDetail' class whether
     * it throws an exception on a missing table key.
     */
    public function testCurrencyDetailMissingTableKeyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Columns for table 'wrongtable' not found.");

        // Act
        $currencyDetail = new CurrencyDetail('wrongtable', $this->tables);
    }

    /**
     * Tests the setter of the 'CurrencyDetail' entity whether
     * it throws an exception on an invalid property.
     */
    public function testCurrencyDetailSetterInvalidPropertyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid property: invalid_property");

        // Act
        $this->currencyDetail->invalid_property = 'test';
    }

    /**
     * Tests the setter of the 'CurrencyDetail' entity whether
     * it throws an exception on an invalid property type.
     */
    public function testCurrencyDetailSetterInvalidTypeException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid type for 'currency_code'. Expected string, got integer");

        // Act
        $this->currencyDetail->currency_code = 5;
    }

    /**
     * Tests the getter of the 'CurrencyDetail' entity whether
     * it throws an exception on an invalid property.
     */
    public function testCurrencyDetailGetterInvalidPropertyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Property 'invalid_property' not existing");

        // Act
        $value = $this->currencyDetail->invalid_property;
    }
}

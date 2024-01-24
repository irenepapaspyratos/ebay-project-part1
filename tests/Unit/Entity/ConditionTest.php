<?php

namespace Tests\Unit\Entity;

use App\Entity\Condition;
use Codeception\Test\Unit;

/**
 * The 'ConditionTest' is a unit test class for testing the 'Condition' class.
 * 
 * Tests the functionality of creating an instance and getting/setting the class-related properties.
 */
class ConditionTest extends Unit {

    protected $tester;
    private $condition;
    private $table = 'ebay_condition';
    private $tables = [
        'ebay_condition' => [
            'columns' => [
                'id' => 'integer',
                'condition_id' => 'integer',
                'condition_display_name' => 'string'
            ]
        ],
    ];

    /**
     * Sets up the necessary environment for running tests by 
     * creating an instance of 'Condition'.
     */
    protected function _before() {

        $this->condition = new Condition($this->table, $this->tables);
    }

    /**
     * Tests whether the 'Condition' instance is created correctly.
     */
    public function testConditionCreation() {

        // Assert that an instance of 'Condition' was created
        $this->assertInstanceOf(Condition::class, $this->condition);
    }

    /**
     * Tests whether the setters in the 'Condition' class modify the properties correctly.
     */
    public function testConditionSettersAndGetters() {

        // Act 
        $this->condition->id = 5;
        $this->condition->condition_id = 333;
        $this->condition->condition_display_name = 'New';

        // Assert that the correct values of the changes are returned
        $this->assertTrue(is_int($this->condition->id));
        $this->assertEquals(5, $this->condition->id);
        $this->assertEquals(333, $this->condition->condition_id);
        $this->assertEquals('New', $this->condition->condition_display_name);
    }

    /**
     * Tests the 'toArray' method of the 'Condition' class whether
     * it converts a Condition object to the correct array.
     */
    public function testConditionToArrayConversion() {

        // Act
        $this->condition->id = 1;
        $this->condition->condition_id = 222;
        $this->condition->condition_display_name = 'Used';

        $expectedArray = [
            'id' => 1,
            'condition_id' => 222,
            'condition_display_name' => 'Used'
        ];

        // Assert that the correct array is returned
        $this->assertEquals($expectedArray, $this->condition->toArray());
    }

    /**
     * Tests the getter of the 'Condition' class whether
     * it throws an exception on a missing table key.
     */
    public function testConditionMissingTableKeyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Columns for table 'wrongtable' not found.");

        // Act
        $condition = new Condition('wrongtable', $this->tables);
    }

    /**
     * Tests the setter of the 'Condition' class whether
     * it throws an exception on an invalid property.
     */
    public function testConditionSetterInvalidPropertyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid property: invalid_property");

        // Act
        $this->condition->invalid_property = 'test';
    }

    /**
     * Tests the setter of the 'Condition' class whether
     * it throws an exception on an invalid property type.
     */
    public function testConditionSetterInvalidTypeException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid type for 'condition_id'. Expected integer, got string");

        // Act
        $this->condition->condition_id = '5';
    }

    /**
     * Tests the getter of the 'Condition' class whether
     * it throws an exception on an invalid property.
     */
    public function testConditionGetterInvalidPropertyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Property 'invalid_property' not existing");

        // Act
        $value = $this->condition->invalid_property;
    }
}

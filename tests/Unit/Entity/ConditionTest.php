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
    private $keyArray;
    private $condition;
    private $conditionWithout;

    /**
     * Sets up the necessary environment for running tests by 
     * creating an array of keys and different instances of 'Condition'.
     */
    protected function _before() {

        $this->keyArray = ['id', 'condition_id', 'condition_display_name'];

        $this->condition = new Condition($this->keyArray, '3000', 'Used', 2);
        $this->conditionWithout = new Condition($this->keyArray, '1000', 'New');
    }


    /**
     * Tests whether the 'Condition' instance is created correctly with and without id.
     */
    public function testConditionCreation() {

        // Assert that an instance of 'Condition' was created
        $this->assertInstanceOf(Condition::class, $this->condition);
        $this->assertInstanceOf(Condition::class, $this->conditionWithout);
    }

    /**
     * Tests whether the getters of the 'Condition' class return the correct values with and without id.
     */
    public function testConditionGetters() {

        // Assert that the getters return the expected value
        $this->assertEquals(2, $this->condition->getId());
        $this->assertEquals(3000, $this->condition->getConditionId());
        $this->assertEquals('Used', $this->condition->getConditionDisplayName());

        $this->assertEquals(null, $this->conditionWithout->getId());
        $this->assertEquals(1000, $this->conditionWithout->getConditionId());
        $this->assertEquals('New', $this->conditionWithout->getConditionDisplayName());
    }

    /**
     * Tests whether the setters in the 'Condition' class modify the properties correctly.
     */
    public function testConditionSetters() {

        // Act 
        $this->condition->setId(5);
        $this->condition->setConditionId(3000);
        $this->condition->setConditionDisplayName('Refurbished');

        // Assert that the correct values of the changes are returned
        $this->assertEquals(5, $this->condition->getId());
        $this->assertEquals(3000, $this->condition->getConditionId());
        $this->assertEquals('Refurbished', $this->condition->getConditionDisplayName());
    }

    /**
     * Tests the 'toArray' method of the 'Condition' class whether
     * it converts a Condition object to the correct array with and without id.
     */
    public function testConditionToArrayConversion() {


        $expectedArray = [
            'id' => 2,
            'condition_id' => 3000,
            'condition_display_name' => 'Used',
        ];

        $expectedArrayWithout = [
            'id' => null,
            'condition_id' => 1000,
            'condition_display_name' => 'New',
        ];

        // Assert
        $this->assertEquals($expectedArray, $this->condition->toArray());
        $this->assertEquals($expectedArrayWithout, $this->conditionWithout->toArray());
    }

    /**
     * Tests the 'toArray' method of the 'Condition' class whether
     * an exception is thrown when trying to convert an object to an array
     * with an invalid key.
     */
    public function testConditionToArrayThrowsExceptionForInvalidKey() {

        // Arrange: Add an invalid key to the keyArray
        $invalidKeyArray = ['id', 'condition_id', 'name'];
        $conditionWithInvalidKey = new Condition($invalidKeyArray, '2000', 'New');

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid Request: Getter for 'name' does not exist in App\Entity\Condition.");

        // Act
        $conditionWithInvalidKey->toArray();
    }
}

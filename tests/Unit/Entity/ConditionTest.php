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

    /**
     * Tests whether the 'Condition' instance is created correctly without id.
     */
    public function testConditionCreationWithoutId() {

        // Act
        $condition = new Condition(null, 3000, 'Used');

        // Assert that an instance of 'Condition' was created
        $this->assertInstanceOf(Condition::class, $condition);
    }

    /**
     * Tests whether the 'Condition' instance is created correctly with id.
     */
    public function testConditionCreationWithId() {

        // Act
        $condition = new Condition(2, 3000, 'Used');

        // Assert that an instance of 'Condition' was created
        $this->assertInstanceOf(Condition::class, $condition);
    }

    /**
     * Tests whether the getters of the 'Condition' class return the correct values without id.
     */
    public function testConditionGettersWithoutId() {

        // Act
        $condition = new Condition(null, 3000, 'Used');

        // Assert that the getters return the expected value
        $this->assertEquals(null, $condition->getId());
        $this->assertEquals(3000, $condition->getConditionId());
        $this->assertEquals('Used', $condition->getConditionDisplayName());
    }

    /**
     * Tests whether the getters of the 'Condition' class return the correct values with id.
     */
    public function testConditionGettersWithId() {

        // Act
        $condition = new Condition(2, 3000, 'Used');

        // Assert that the getters return the expected value
        $this->assertEquals(2, $condition->getId());
        $this->assertEquals(3000, $condition->getConditionId());
        $this->assertEquals('Used', $condition->getConditionDisplayName());
    }

    /**
     * Tests whether the setters in the 'Condition' class modify the properties correctly.
     */
    public function testConditionSettersModifyProperties() {

        // Arrange
        $condition = new Condition(2, 3000, 'Used');

        // Act 
        $condition->setId(1);
        $condition->setConditionId(1000);
        $condition->setConditionDisplayName('New');

        // Assert that the correct values of the changes are returned
        $this->assertEquals(1, $condition->getId());
        $this->assertEquals(1000, $condition->getConditionId());
        $this->assertEquals('New', $condition->getConditionDisplayName());
    }

    /**
     * Tests the 'toArray' method of the 'Condition' class whether
     * it converts a Condition object to the correct array without id.
     */
    public function testToArrayConversionWithoutId() {

        // Act
        $condition = new Condition(null, 3000, 'Used');

        $expectedArray = [
            'id' => null,
            'condition_id' => 3000,
            'condition_display_name' => 'Used',
        ];

        // Assert
        $this->assertEquals($expectedArray, $condition->toArray());
    }

    /**
     * Tests the 'toArray' method of the 'Condition' class whether
     * it converts a Condition object to the correct array with id.
     */
    public function testToArrayConversionWithId() {

        // Act
        $condition = new Condition(1, 1000, 'New');

        $expectedArray = [
            'id' => 1,
            'condition_id' => 1000,
            'condition_display_name' => 'New',
        ];

        // Assert
        $this->assertEquals($expectedArray, $condition->toArray());
    }
}

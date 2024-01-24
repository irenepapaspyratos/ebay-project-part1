<?php

namespace Tests\Unit\Entity;

use App\Entity\Category;
use Codeception\Test\Unit;

/**
 * The 'CategoryTest' is a unit test class for testing the 'Category' class.
 * 
 * Tests the functionality of creating an instance and getting/setting the class-related properties.
 */
class CategoryTest extends Unit {

    protected $tester;
    private $category;
    private $table = 'ebay_category';
    private $tables = [
        'ebay_category' => [
            'columns' => [
                'id' => 'integer',
                'category_id' => 'integer',
                'category_level' => 'integer',
                'category_name' => 'string',
                'leaf_category' => 'boolean',
                'parent_category_id' => 'integer',
                'fk_parent_id' => 'integer',
            ]
        ],
    ];

    /**
     * Sets up the necessary environment for running tests by 
     * creating an instance of 'Category'.
     */
    protected function _before() {

        $this->category = new Category($this->table, $this->tables);
    }

    /**
     * Tests whether the 'Category' instance is created correctly.
     */
    public function testCategoryCreation() {

        // Assert that an instance of 'Category' was created
        $this->assertInstanceOf(Category::class, $this->category);
    }

    /**
     * Tests whether the setters in the 'Category' class modify the properties correctly.
     */
    public function testCategorySettersAndGetters() {

        // Act 
        $this->category->id = 5;
        $this->category->category_id = 333;
        $this->category->category_level = 2;
        $this->category->category_name = 'Cotton';
        $this->category->leaf_category = true;
        $this->category->parent_category_id = 4;
        $this->category->fk_parent_id = 4;

        // Assert that the correct values of the changes are returned
        $this->assertTrue(is_int($this->category->id));
        $this->assertEquals(5, $this->category->id);
        $this->assertEquals(333, $this->category->category_id);
        $this->assertEquals(2, $this->category->category_level);
        $this->assertEquals('Cotton', $this->category->category_name);
        $this->assertEquals(true, $this->category->leaf_category);
        $this->assertEquals(4, $this->category->parent_category_id);
        $this->assertEquals(4, $this->category->fk_parent_id);
    }

    /**
     * Tests the 'toArray' method of the 'Category' class whether
     * it converts a Category object to the correct array.
     */
    public function testCategoryToArrayConversion() {

        // Act
        $this->category->id = 1;
        $this->category->category_id = 222;
        $this->category->category_level = 2;
        $this->category->category_name = 'Silk';
        $this->category->leaf_category = false;
        $this->category->parent_category_id = 3;
        $this->category->fk_parent_id = 3;

        $expectedArray = [
            'id' => 1,
            'category_id' => 222,
            'category_level' => 2,
            'category_name' => 'Silk',
            'leaf_category' => false,
            'parent_category_id' => 3,
            'fk_parent_id' => 3
        ];

        // Assert that the correct array is returned
        $this->assertEquals($expectedArray, $this->category->toArray());
    }

    /**
     * Tests the getter of the 'Category' class whether
     * it throws an exception on a missing table key.
     */
    public function testCategoryMissingTableKeyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Columns for table 'wrongtable' not found.");

        // Act
        $category = new Category('wrongtable', $this->tables);
    }

    /**
     * Tests the setter of the 'Category' class whether
     * it throws an exception on an invalid property.
     */
    public function testCategorySetterInvalidPropertyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid property: invalid_property");

        // Act
        $this->category->invalid_property = 'test';
    }

    /**
     * Tests the setter of the 'Category' class whether
     * it throws an exception on an invalid property type.
     */
    public function testCategorySetterInvalidTypeException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid type for 'category_id'. Expected integer, got string");

        // Act
        $this->category->category_id = '5';
    }

    /**
     * Tests the getter of the 'Category' class whether
     * it throws an exception on an invalid property.
     */
    public function testCategoryGetterInvalidPropertyException() {

        // Assert that an exception of type `\Exception` is thrown containing the correct message 
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Property 'invalid_property' not existing");

        // Act
        $value = $this->category->invalid_property;
    }
}

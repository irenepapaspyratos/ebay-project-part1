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
    private $keyArray;
    private $category;
    private $categoryWithout;

    /**
     * Sets up the necessary environment for running tests by 
     * creating an array of keys and different instances of 'Category'.
     */
    protected function _before() {

        $this->keyArray = ['id', 'category_id', 'category_name', 'parent_id'];

        $this->category = new Category($this->keyArray, '222', 'Silk', 1, 3);
        $this->categoryWithout = new Category($this->keyArray, '111', 'Cotton');
    }

    /**
     * Tests whether the 'Category' instance is created correctly with and without id and parent.
     */
    public function testCategoryCreation() {

        // Assert that an instance of 'Category' was created
        $this->assertInstanceOf(Category::class, $this->category);
        $this->assertInstanceOf(Category::class, $this->categoryWithout);
    }

    /**
     * Tests whether the getters of the 'Category' class return the correct values 
     * with and without id and parent category provided.
     */
    public function testCategoryGetters() {

        // Assert that the getters return the expected value
        $this->assertEquals(3, $this->category->getId());
        $this->assertEquals('222', $this->category->getCategoryId());
        $this->assertEquals('Silk', $this->category->getCategoryName());
        $this->assertEquals(1, $this->category->getParentId());

        $this->assertEquals(null, $this->categoryWithout->getId());
        $this->assertEquals('111', $this->categoryWithout->getCategoryId());
        $this->assertEquals('Cotton', $this->categoryWithout->getCategoryName());
        $this->assertEquals(0, $this->categoryWithout->getParentId());
    }

    /**
     * Tests whether the setters in the 'Category' class modify the properties correctly.
     */
    public function testCategorySetters() {

        // Act 
        $this->categoryWithout->setId(3);
        $this->categoryWithout->setCategoryId('222');
        $this->categoryWithout->setCategoryName('Silk');
        $this->categoryWithout->setParentId(1);

        // Assert that the correct values of the changes are returned
        $this->assertEquals(3, $this->categoryWithout->getId());
        $this->assertEquals('222', $this->categoryWithout->getCategoryId());
        $this->assertEquals('Silk', $this->categoryWithout->getCategoryName());
        $this->assertEquals(1, $this->categoryWithout->getParentId());
    }

    /**
     * Tests the 'toArray' method of the 'Category' class whether
     * it converts a Category object to the correct array with and without id and parent category.
     */
    public function testCategoryToArrayConversion() {

        $expectedArray = [
            'id' => 3,
            'category_id' => '222',
            'category_name' => 'Silk',
            'parent_id' => 1
        ];

        $expectedArrayWithout = [
            'id' => null,
            'category_id' => '111',
            'category_name' => 'Cotton',
            'parent_id' => 0
        ];

        // Assert
        $this->assertEquals($expectedArray, $this->category->toArray());
        $this->assertEquals($expectedArrayWithout, $this->categoryWithout->toArray());
    }

    /**
     * Tests the 'toArray' method of the 'Category' class whether
     * an exception is thrown when trying to convert an object to an array
     * with an invalid key.
     */
    public function testCategoryToArrayThrowsExceptionForInvalidKey() {

        // Arrange: Add an invalid key to the keyArray
        $invalidKeyArray = ['id', 'category_id', 'category_name', 'parent'];
        $categoryWithInvalidKey = new Category($invalidKeyArray, '222', 'Silk', 1, 3);

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid Request: Getter for 'parent' does not exist in App\Entity\Category.");

        // Act
        $categoryWithInvalidKey->toArray();
    }
}

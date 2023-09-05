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

    /**
     * Tests whether the 'Category' instance is created correctly without id and parent.
     */
    public function testCategoryCreationWithoutIdAndParent() {

        // Act
        $category = new Category(null, '111', 'Cotton');

        // Assert that an instance of 'Category' was created
        $this->assertInstanceOf(Category::class, $category);
    }

    /**
     * Tests whether the 'Category' instance is created correctly with id and parent.
     */
    public function testCategoryCreationWithIdAndParent() {

        // Act
        $category = new Category(2, '111', 'Cotton', 1);

        // Assert that an instance of 'Category' was created
        $this->assertInstanceOf(Category::class, $category);
    }

    /**
     * Tests whether the getters of the 'Category' class return the correct values 
     * with no id or parent category provided.
     */
    public function testCategoryGettersWithoutIdAndParent() {

        // Act
        $category = new Category(null, '111', 'Cotton');

        // Assert that the getters return the expected value
        $this->assertEquals(null, $category->getId());
        $this->assertEquals('111', $category->getCategoryId());
        $this->assertEquals('Cotton', $category->getCategoryName());
        $this->assertEquals(0, $category->getParentId());
    }

    /**
     * Tests whether the getters of the 'Category' class return correct values 
     * with id and parent category provided.
     */
    public function testCategoryGettersWithIdAndParent() {

        // Act
        $category = new Category(2, '111', 'Cotton', 1);

        // Assert that the getters return the expected value
        $this->assertEquals(2, $category->getId());
        $this->assertEquals('111', $category->getCategoryId());
        $this->assertEquals('Cotton', $category->getCategoryName());
        $this->assertEquals(1, $category->getParentId());
    }

    /**
     * Tests whether the setters in the 'Category' class modify the properties correctly.
     */
    public function testCategorySettersModifyProperties() {

        // Arrange
        $category = new Category(2, '111', 'Cotton');

        // Act 
        $category->setId(3);
        $category->setCategoryId('222');
        $category->setCategoryName('Silk');
        $category->setParentId(1);

        // Assert that the correct values of the changes are returned
        $this->assertEquals(3, $category->getId());
        $this->assertEquals('222', $category->getCategoryId());
        $this->assertEquals('Silk', $category->getCategoryName());
        $this->assertEquals(1, $category->getParentId());
    }

    /**
     * Tests the 'toArray' method of the 'Category' class whether
     * it converts a Category object to the correct array without id and parent category.
     */
    public function testToArrayConversionWithoutIdAndParent() {

        // Act
        $category = new Category(null, '111', 'Cotton');

        $expectedArray = [
            'id' => null,
            'category_id' => '111',
            'category_name' => 'Cotton',
            'parent_id' => 0
        ];

        // Assert
        $this->assertEquals($expectedArray, $category->toArray());
    }

    /**
     * Tests the 'toArray' method of the 'Category' class whether
     * it converts a Category object to the correct array with id and parent category.
     */
    public function testToArrayConversionWithIdAndParent() {

        // Act
        $category = new Category(3, '222', 'Silk', 1);

        $expectedArray = [
            'id' => 3,
            'category_id' => '222',
            'category_name' => 'Silk',
            'parent_id' => 1
        ];

        // Assert
        $this->assertEquals($expectedArray, $category->toArray());
    }
}

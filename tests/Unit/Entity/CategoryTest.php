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
     * Tests whether the 'Category' instance is created correctly.
     */
    public function testCategoryCreationWithoutParent() {

        // Act
        $category = new Category(2, '111', 'Cotton');

        // Assert that an instance of 'Category' was created
        $this->assertInstanceOf(Category::class, $category);
    }

    /**
     * Tests whether the 'Category' instance is created correctly.
     */
    public function testCategoryCreationWithParent() {

        // Act
        $category = new Category(2, '111', 'Cotton', 1);

        // Assert that an instance of 'Category' was created
        $this->assertInstanceOf(Category::class, $category);
    }

    /**
     * Tests whether the getters of the 'Category' class return the correct values 
     * with no parent category provided.
     */
    public function testCategoryGettersWithoutParent() {

        // Act
        $category = new Category(2, '111', 'Cotton');

        // Assert that the getters return the expected value
        $this->assertEquals(2, $category->getId());
        $this->assertEquals('111', $category->getCategoryId());
        $this->assertEquals('Cotton', $category->getName());
        $this->assertEquals(0, $category->getParentId());
    }

    /**
     * Tests whether the getters of the 'Category' class return correct values 
     * with a parent category provided.
     */
    public function testCategoryGettersWithParent() {

        // Act
        $category = new Category(2, '111', 'Cotton', 1);

        // Assert that the getters return the expected value
        $this->assertEquals(2, $category->getId());
        $this->assertEquals('111', $category->getCategoryId());
        $this->assertEquals('Cotton', $category->getName());
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
        $category->setName('Silk');
        $category->setParentId(1);

        // Assert that the correct values of the changes are returned
        $this->assertEquals(3, $category->getId());
        $this->assertEquals('222', $category->getCategoryId());
        $this->assertEquals('Silk', $category->getName());
        $this->assertEquals(1, $category->getParentId());
    }

    /**
     * Tests the 'toArray' method of the 'Category' class whether
     * it converts a Category object to the correct array without a parent category.
     */
    public function testToArrayConversionWithoutParent() {

        // Act
        $category = new Category(2, '111', 'Cotton');

        $expectedArray = [2, '111', 'Cotton', 0];

        // Assert
        $this->assertEquals($expectedArray, $category->toArray());
    }

    /**
     * Tests the 'toArray' method of the 'Category' class whether
     * it converts a Category object to the correct array with a parent category.
     */
    public function testToArrayConversionWithParent() {

        // Act
        $category = new Category(3, '222', 'Silk', 1);

        $expectedArray = [3, '222', 'Silk', 1];

        // Assert
        $this->assertEquals($expectedArray, $category->toArray());
    }
}

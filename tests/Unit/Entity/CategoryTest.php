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
     * Tests wether the 'Category' instance is created correctly.
     */
    public function testCategoryCreation() {

        // Act
        $category = new Category(2, '111', 'Cotton');

        // Assert that an instance of 'Category' was created
        $this->assertInstanceOf(Category::class, $category);
    }

    /**
     * Tests wether the getters of the 'Category' class return the correct values 
     * with no parent category provided.
     */
    public function testCategoryGettersReturnCorrectValuesWithoutParents() {

        // Act
        $categoryA = new Category(2, '111', 'Cotton');

        // Assert that the getters return the expected value
        $this->assertEquals(2, $categoryA->getId());
        $this->assertEquals('111', $categoryA->getCategoryId());
        $this->assertEquals('Cotton', $categoryA->getName());
        $this->assertEquals(null, $categoryA->getParentId());
        $this->assertEquals(null, $categoryA->getParentPath());
        $this->assertEquals('Cotton', $categoryA->getFullPath());
    }

    /**
     * Tests wether the getters of the 'Category' class return correct values 
     * with a parent category privided.
     */
    public function testCategoryGettersReturnCorrectValuesWithParents() {

        // Act
        $categoryB = new Category(2, '111', 'Cotton', 1, 'Fabric');

        // Assert that the getters return the expected value
        $this->assertEquals(2, $categoryB->getId());
        $this->assertEquals('111', $categoryB->getCategoryId());
        $this->assertEquals('Cotton', $categoryB->getName());
        $this->assertEquals(1, $categoryB->getParentId());
        $this->assertEquals('Fabric', $categoryB->getParentPath());
        $this->assertEquals('Fabric>Cotton', $categoryB->getFullPath());
    }

    /**
     * Tests wether the setters in the 'Category' class modify the properties correctly.
     */
    public function testCategorySettersModifyProperties() {

        // Arrange
        $category = new Category(2, '111', 'Cotton');

        // Act 
        $category->setId(3);
        $category->setCategoryId('222');
        $category->setName('Silk');
        $category->setParentId(1);
        $category->setParentPath('Fabric');

        // Assert that the correct values of the changes are returned
        $this->assertEquals(3, $category->getId());
        $this->assertEquals('222', $category->getCategoryId());
        $this->assertEquals('Silk', $category->getName());
        $this->assertEquals(1, $category->getParentId());
        $this->assertEquals('Fabric>Silk', $category->getFullPath());
    }
}

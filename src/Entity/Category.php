<?php

namespace App\Entity;

use App\Interface\Entity;
use App\Trait\ToArrayTrait;

/**
 * The `Category` class provides methods to deal with categories.
 * 
 * The contained methods are getting/setting its properties
 * and convert them to an array using the 'ToArrayTrait'.
 */
class Category implements Entity {

    private int|null $id;
    private string $categoryId;
    private string $categoryName;
    private int $parentId;
    private array $keyArray;

    /**
     * The '__construct' method initializes properties with corresponding values, either defaults or passed as arguments.
     *
     * @param array<int,string> $keyArray Representing valid column names of the corresponding database table.
     * @param string $categoryId Portal's unique identifier of a category.
     * @param string $categoryName Portal's (sub)category's name. If it is a subcategory, the whole path can be retrieved by recursively following the parent ids.
     * @param int $parentId Id of the possible parent category with 0 representing the category not being a subcategory and therefore not having a parent (Default = 0).
     * @param int|null $id Primary Key, possibly empty as coming from the database (Default = null).
     * 
     * @return void
     */
    public function __construct(array $keyArray, string $categoryId, string $categoryName, int $parentId = 0, int|null $id = null) {

        $this->id = $id;
        $this->categoryId = $categoryId;
        $this->categoryName = $categoryName;
        $this->parentId = $parentId;
        $this->keyArray = $keyArray;
    }


    // Getters
    public function getId(): int|null {
        return $this->id;
    }

    public function getCategoryId(): string {
        return $this->categoryId;
    }

    public function getCategoryName(): string {
        return $this->categoryName;
    }

    public function getParentId(): int {
        return $this->parentId;
    }


    // Setters
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setCategoryId(string $categoryId): void {
        $this->categoryId = $categoryId;
    }

    public function setCategoryName(string $categoryName): void {
        $this->categoryName = $categoryName;
    }

    public function setParentId(int $parentId): void {
        $this->parentId = $parentId;
    }


    // Import and use the 'toArray' method of the `ToArrayTrait` trait.
    use ToArrayTrait;
}

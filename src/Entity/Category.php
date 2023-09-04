<?php

namespace App\Entity;

/**
 * The `Category` class provides methods to deal with categories.
 * 
 * The contained methods are getting/setting its properties
 * and get/set the full path of the category name including its optional parent(s).
 */
class Category {
    private $id;
    private $categoryId;
    private $name;
    private $parentId;
    private $parentPath;

    /**
     * The '__construct' method initializes properties with corresponding values, either defaults or passed as arguments.
     *
     * A category can have parents and the parent path would look like this example for the category Silk: Fabric>Fine.
     * 
     * @param int $id Primary Key.
     * @param string $categoryId Category's id.
     * @param string $name Category's name.
     * @param string|null $parentId Optional: id of the parent category.
     * @param string|null $parentPath Optional: full name path of the parent(s) separated with '>'.
     * 
     * @return void
     */
    public function __construct(string $id, string $categoryId, string $name, ?string $parentId = null, ?string $parentPath = null) {
        $this->id = $id;
        $this->categoryId = $categoryId;
        $this->name = $name;
        $this->parentId = $parentId;
        $this->parentPath = $parentPath;
    }

    /**
     * Get the full 'named path' of the category and its parents.
     *
     * @return string
     */
    public function getFullPath(): string {
        return ($this->parentPath === null || $this->parentPath === '') ?
            $this->name : $this->parentPath . '>' . $this->name;
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getCategoryId(): string {
        return $this->categoryId;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getParentId(): ?string {
        return ($this->parentId === null || $this->parentId === '') ?
            null : $this->parentId;
    }

    public function getParentPath(): ?string {
        return ($this->parentPath === null || $this->parentPath === '') ?
            null : $this->parentPath;
    }

    // Setters
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setCategoryId(string $categoryId): void {
        $this->categoryId = $categoryId;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setParentId(?string $parentId): void {
        $this->parentId = $parentId;
    }

    public function setParentPath(?string $parentPath): void {
        $this->parentPath = $parentPath;
    }
}

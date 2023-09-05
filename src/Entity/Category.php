<?php

namespace App\Entity;

/**
 * The `Category` class provides methods to deal with categories.
 * 
 * The contained methods are getting/setting its properties
 * and convert them to an array.
 */
class Category implements Entity {

    private $id;
    private $categoryId;
    private $name;
    private $parentId;

    /**
     * The '__construct' method initializes properties with corresponding values, either defaults or passed as arguments.
     *
     * A category can have parents and the parent path would look like this example for the category Silk: Fabric>Fine.
     * 
     * @param ?int $id Primary Key, possibly empty as coming from the database.
     * @param string $categoryId The portal's category id.
     * @param string $name (Sub)Category's name. If it is a subcategory, the whole path can be retrieved by recursively follow the parent ids.
     * @param int $parentId Id of the possible parent category with 0 representing the category not being a subcategory.
     * 
     * @return void
     */
    public function __construct(?int $id, string $categoryId, string $name, int $parentId = 0) {
        $this->id = $id;
        $this->categoryId = $categoryId;
        $this->name = $name;
        $this->parentId = $parentId;
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

    public function getParentId(): int {
        return $this->parentId;
    }


    // Setters
    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function setCategoryId(string $categoryId): void {
        $this->categoryId = $categoryId;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setParentId(int $parentId): void {
        $this->parentId = $parentId;
    }

    /**
     * The 'toArray' method converts the object of the class to an array.
     * 
     * @return array Array representation of the object.
     */
    public function toArray(): array {
        return [
            $this->getId(),
            $this->getCategoryId(),
            $this->getName(),
            $this->getParentId(),
        ];
    }
}

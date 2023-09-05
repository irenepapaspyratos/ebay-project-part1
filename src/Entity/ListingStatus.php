<?php

namespace App\Entity;

/**
 * The `ListingStatus` class provides methods to deal with codes specifying status types.
 * 
 * The contained methods are getting/setting its properties
 * and convert them to an array.
 */
class ListingStatus implements Entity {

    private ?int $id;
    private string $codeType;
    private string $description;

    /**
     * The '__construct' method initializes properties with corresponding values, either defaults or passed as arguments.
     *
     * @param ?int $id Primary Key, possibly empty as coming from the database.
     * @param string $codeType Status code of a listings (like "Active", etc.).
     * @param string $description Explanation of the status code.
     * 
     * @return void
     */
    public function __construct(?int $id, string $codeType, string $description) {

        $this->id = $id;
        $this->codeType = $codeType;
        $this->description = $description;
    }


    // Getters
    public function getId(): int|null {
        return $this->id;
    }

    public function getCodeType(): string {
        return $this->codeType;
    }

    public function getDescription(): string {
        return $this->description;
    }


    // Setters
    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function setCodeType(string $codeType): void {
        $this->codeType = $codeType;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }


    /**
     * The 'toArray' method converts the object of the class to an array.
     * 
     * @return array Array representation of the object.
     */
    public function toArray(): array {

        return [
            'id' => $this->getId(),
            'code_type' => $this->getCodeType(),
            'description' => $this->getDescription(),
        ];
    }
}

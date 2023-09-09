<?php

namespace App\Entity;

use App\Interface\Entity;
use App\Trait\ToArrayTrait;

/**
 * The `ListingStatus` class provides methods to deal with codes specifying status types.
 * 
 * The contained methods are getting/setting its properties
 * and convert them to an array using the 'ToArrayTrait'.
 */
class ListingStatus implements Entity {

    private array $keyArray;
    private int|null $id;
    private string $statusCode;
    private string $description;

    /**
     * The '__construct' method initializes properties with corresponding values, either defaults or passed as arguments.
     *
     * @param string $statusCode Status code of a listings (like "Active", etc.).
     * @param string $description Explanation of the status code.
     * @param int|null $id Primary Key, possibly empty as coming from the database (Default = null).
     * 
     * @return void
     */
    public function __construct(array $keyArray, string $statusCode, string $description, int|null $id = null) {

        $this->id = $id;
        $this->statusCode = $statusCode;
        $this->description = $description;
        $this->keyArray = $keyArray;
    }


    // Getters
    public function getId(): int|null {
        return $this->id;
    }

    public function getStatusCode(): string {
        return $this->statusCode;
    }

    public function getStatusDescription(): string {
        return $this->description;
    }


    // Setters
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setStatusCode(string $statusCode): void {
        $this->statusCode = $statusCode;
    }

    public function setStatusDescription(string $description): void {
        $this->description = $description;
    }


    // Import and use the 'toArray' method of the `ToArrayTrait` trait.
    use ToArrayTrait;
}

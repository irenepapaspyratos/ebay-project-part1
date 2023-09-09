<?php

namespace App\Entity;

use App\Interface\Entity;
use App\Trait\ToArrayTrait;

/**
 * The `Condition` class provides methods to deal with codes specifying a condition.
 * 
 * The contained methods are getting/setting its properties
 * and convert them to an array using the 'ToArrayTrait'.
 */
class Condition implements Entity {

    private int|null $id;
    private string $conditionId;
    private string $conditionDisplayName;
    private array $keyArray;

    /**
     * The '__construct' method initializes properties with corresponding values, either defaults or passed as arguments.
     *
     * @param array<int,string> $keyArray Representing valid column names of the corresponding database table.
     * @param string $conditionId Unique identifier of a condition (like 1000 for "New", etc.).
     * @param string $conditionDisplayName States the typical meaning. However, values can differ per category.
     * @param int|null $id Primary Key, possibly empty as coming from the database (Default = null).
     * 
     * @return void
     */
    public function __construct(array $keyArray, int $conditionId, string $conditionDisplayName, int|null $id = null) {

        $this->id = $id;
        $this->conditionId = $conditionId;
        $this->conditionDisplayName = $conditionDisplayName;
        $this->keyArray = $keyArray;
    }


    // Getters
    public function getId(): int|null {
        return $this->id;
    }

    public function getConditionId(): int {
        return $this->conditionId;
    }

    public function getConditionDisplayName(): string {
        return $this->conditionDisplayName;
    }


    // Setters
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setConditionId(int $conditionId): void {
        $this->conditionId = $conditionId;
    }

    public function setConditionDisplayName(string $conditionDisplayName): void {
        $this->conditionDisplayName = $conditionDisplayName;
    }


    // Import and use the 'toArray' method of the `ToArrayTrait` trait.
    use ToArrayTrait;
}

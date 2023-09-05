<?php

namespace App\Entity;

/**
 * The `Condition` class provides methods to deal with codes specifying a condition.
 * 
 * The contained methods are getting/setting its properties
 * and convert them to an array.
 */
class Condition implements Entity {

    private $id;
    private $conditionId;
    private $conditionDisplayName;

    /**
     * The '__construct' method initializes properties with corresponding values, either defaults or passed as arguments.
     *
     * @param ?int $id Primary Key, possibly empty as coming from the database.
     * @param string $conditionId Represents the condition code.
     * @param string $conditionDisplayName States the typical meaning. However, values can differ per category.
     * 
     * @return void
     */
    public function __construct(?int $id, int $conditionId, string $conditionDisplayName) {

        $this->id = $id;
        $this->conditionId = $conditionId;
        $this->conditionDisplayName = $conditionDisplayName;
    }


    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getConditionId(): int {
        return $this->conditionId;
    }

    public function getConditionDisplayName(): string {
        return $this->conditionDisplayName;
    }


    // Setters
    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function setConditionId(int $conditionId): void {
        $this->conditionId = $conditionId;
    }

    public function setConditionDisplayName(string $conditionDisplayName): void {
        $this->conditionDisplayName = $conditionDisplayName;
    }


    /**
     * The 'toArray' method converts the object of the class to an array.
     * 
     * @return array Array representation of the object.
     */
    public function toArray(): array {

        return [
            'id' => $this->getId(),
            'condition_id' => $this->getConditionId(),
            'condition_display_name' => $this->getConditionDisplayName(),
        ];
    }
}

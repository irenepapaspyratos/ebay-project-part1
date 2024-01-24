<?php

namespace App\Entity;

/**
 * The 'BaseEntity' class is an abstract base class. 
 * 
 * Provides functionality for 'magically' setting and getting properties, 
 * as well as converting the object to an array with keys taken from the configuration file. 
 * Auto-converts DateTime to UTC Timezone!
 */
abstract class BaseEntity {

    protected string $table;
    protected array $validTables;
    protected array $validColumns = [];
    protected array $data = [];

    /**
     * The '__construct' method initializes properties with corresponding values, either defaults or passed as arguments.
     * 
     * Automatically creates an array with valid columns/keys for each class that extends this 'BaseEntity'. 
     * To achieve this, the class name must correspond with a table key in the configuration file. 
     * 
     * @param string $table Represents the table name for which the valid column array has to be created.
     * @param array $validTables Each table as key contains the corresponding valid columns (a.o.) as value.
     * 
     * @return void
     */
    public function __construct(string $table, array $validTables) {

        $this->table = $table;
        $this->validTables = $validTables;

        // Extract the valid columns from the configuration
        if (!isset($validTables[$this->table]['columns']))
            throw new \Exception("Columns for table '{$this->table}' not found.");

        $this->validColumns = $validTables[$this->table]['columns'];
    }

    /**
     * The '__set' method sets the value for a property after checking its correct type.
     * 
     * @param string $name Property to be set.
     * @param int|string|float|null $value Value to be set for the given property. 
     * 
     * @return void
     * @throws \Exception If an invalid property or type is tried to be set.
     */
    public function __set(string $name, int|string|float|\DateTime|null $value): void {

        // Validate property
        if (!array_key_exists($name, $this->validColumns))
            throw new \Exception("Invalid property: $name");

        $expectedType = $this->validColumns[$name];
        $actualType = gettype($value);

        // Validate special cases
        if ($actualType === 'NULL') {
            $expectedType = 'NULL';
        } else {

            switch ($expectedType) {

                case 'DateTime':

                    // Validate DateTime and set UTC timezone
                    if ($actualType === 'object' && $value instanceof \DateTime) {
                        $expectedType = 'object';
                        $value->setTimezone(new \DateTimeZone('UTC'));
                    }
                    break;

                case 'float':
                    $expectedType = 'double';
                    break;

                case 'boolean':
                    $expectedType = 'integer';
                    break;

                case 'JSON':
                    $expectedType = 'string';

                    // Validate JSON
                    if (json_decode($value) === null && json_last_error() !== JSON_ERROR_NONE)
                        throw new \InvalidArgumentException("Invalid JSON data for '$name'");
                    break;
            }
        }

        // Validate type
        if ($actualType !== $expectedType)
            throw new \InvalidArgumentException("Invalid type for '$name'. Expected $expectedType, got $actualType");

        $this->data[$name] = $value;
    }

    /**
     * The '__get' method gets the value of a property.
     * 
     * @param string $name Property to get the value for.
     * 
     * @return int|string|float|null Value of the requested property, defaults to null for non-set properties. 
     * @throws \Exception If an invalid property is requested.
     */
    public function __get(string $name): int|string|float|\DateTime|null {

        if (!array_key_exists($name, $this->validColumns))
            throw new \Exception("Property '$name' not existing");

        return $this->data[$name] ?? null;
    }

    /**
     * The 'toArray' method converts the data object to an array 
     * with all keys based on the list of valid columns (value of non-set property: null).
     * 
     * @return array<string,int|string|float|null> Array representation of the object.
     */
    public function toArray(): array {

        $output = [];
        foreach (array_keys($this->validColumns) as $key)
            $output[$key] = $this->$key instanceof \DateTime ? $this->$key->format('Y-m-d H:i:s') : $this->$key;

        return $output;
    }
}

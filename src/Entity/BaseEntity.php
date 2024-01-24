<?php

namespace App\Entity;

/**
 * The 'BaseEntity' class is an abstract base class. 
 * 
 * Provides functionality for 'magically' setting and getting properties, 
 * as well as converting the object to an array with keys taken from the configuration file. 
 */
abstract class BaseEntity {

    private $prefix;
    private $validColumns = [];
    private $data = [];

    /**
     * The '__construct' method initializes properties with corresponding values, either defaults or passed as arguments.
     * 
     * Automatically creates an array with valid columns/keys for each class that extends this 'BaseEntity'. 
     * To achieve this, the class name combined with the provided prefix must correspond with a table key in the configuration file. 
     * 
     * For instance, an entity for eBay categories would require the prefix 'ebay_' and needs to be named 'Category' 
     * if the corresponding table key in the configuration file is set as 'ebay_category' (corresponding to the related table in the database).
     *     
     * @param string $prefix Prefix for the table key in the configuration file.
     * 
     * @return void
     */
    public function __construct(string $prefix) {

        $this->prefix = $prefix;

        // Load the configuration file
        $config = include __DIR__ . '/../../config.php';

        // Get the class name 
        $className = (new \ReflectionClass($this))->getShortName();

        // Convert camel case to snake case and create the key for the table array in the configuration file
        $tableName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $className));
        $tableKey = $this->prefix . $tableName;

        // Extract the valid columns from the configuration
        if (!isset($config['database']['table'][$tableKey]['columns']))
            throw new \Exception("Columns for table '$tableKey' not found.");

        $this->validColumns = $config['database']['table'][$tableKey]['columns'];
    }

    /**
     * The '__set' method sets the value for a property after checking its correct type.
     * 
     * @param string $name Property to be set.
     * @param int|string|float|null $value Value to be set for the given property. 
     * 
     * @return void
     * @throws \Exception If an invalid property is tried to be set.
     */
    public function __set(string $name, int|string|float|null $value): void {

        if (!array_key_exists($name, $this->validColumns))
            throw new \Exception("Invalid property: $name");

        $expectedType = $this->validColumns[$name];
        $actualType = gettype($value);

        // Special cases as 'gettype' returns 'double' for float and 'string' for JSON
        if ($actualType === 'double')
            $actualType = 'float';

        if ($expectedType === 'JSON') {

            $expectedType = 'string';

            // Validate JSON
            if (json_decode($value) === null && json_last_error() !== JSON_ERROR_NONE) {
                throw new \InvalidArgumentException("Invalid JSON data for '$name'");
            }
        }

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
    public function __get(string $name): int|string|float|null {

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
            $output[$key] = $this->$key;

        return $output;
    }
}

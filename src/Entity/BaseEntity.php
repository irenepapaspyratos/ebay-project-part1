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
     * To achieve this, the class name combined with the provided prefix should correspond with a table key in the configuration file. 
     * For instance, an entity for eBay categories would require the prefix 'ebay_' and needs to be named 'Category' 
     * if the corresponding table name in the database is set as 'ebay_category' in the configuration file.
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
        $className = strtolower((new \ReflectionClass($this))->getShortName());

        // Create the key for the table array in the configuration file
        $tableKey = $this->prefix . $className;

        // Extract the valid columns from the configuration
        if (!isset($config['database']['tables'][$tableKey]['columns']))
            throw new \Exception("Columns for table $tableKey not found.");

        $this->validColumns = array_keys($config['database']['tables'][$tableKey]['columns']);
    }

    /**
     * The '__set' method sets the value for a property.
     * 
     * @param string $name Property to be set.
     * @param int|string|float|null $value Value to be set for the given property. 
     * 
     * @return void
     * @throws \Exception If an invalid property is tried to be set.
     */
    public function __set(string $name, int|string|float|null $value): void {

        if (!in_array($name, $this->validColumns))
            throw new \Exception("Invalid property: $name");

        $this->data[$name] = $value;
    }

    /**
     * The '__get' method gets the value of a property.
     * 
     * @param string $name Property to get the value for.
     * 
     * @return int|string|float|null Value of the requested property. 
     * @throws \Exception If an invalid property is requested.
     */
    public function __get(string $name): int|string|float|null {

        if (!array_key_exists($name, $this->data))
            throw new \Exception("Property $name not existing");

        return $this->data[$name];
    }

    /**
     * The 'toArray' method converts the data object to an array 
     * with keys based on the list of valid columns.
     * 
     * @return array<string,int|string|float|null> Array representation of the object.
     * @throws \Exception If a property can't be accessed.
     */
    public function toArray(): array {

        $output = [];
        foreach ($this->validColumns as $key) {

            try {

                $output[$key] = $this->$key;
            } catch (\Exception $e) {

                throw new \Exception("Error accessing property '$key': " . $e->getMessage());
            }
        }

        return $output;
    }
}

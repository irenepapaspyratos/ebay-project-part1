<?php

namespace App\Repository;

use PDO;
use App\Database\DatabaseConnection;
use App\Entity\BaseEntity;

/**
 * The `BaseRepository` class provides methods for interacting with a database.
 * 
 * The contained methods are providing basic operations with prepared statements to 
 * add, update, delete and find elements in a database.
 */
abstract class BaseRepository {

    protected $dbConnection;
    protected $table;
    protected $validation = [];
    protected $validColumns;


    /**
     * The '__construct' method initializes properties with corresponding values, either defaults or passed as arguments.
     * 
     * It initializes the database connection and assigns the table to work with
     * 
     * @param DatabaseConnection $dbConnection The connection parameter is an instance of the PDO class.
     * @param string $table Name of the database table that the class will be using.
     * @param array $validation Array with valid table and column names.
     * 
     * @return void
     * 
     */
    public function __construct(DatabaseConnection $dbConnection, string $table, array $validation) {

        $this->dbConnection = $dbConnection->getConnection();

        // Check if the table is valid
        if (!isset($validation['database']['tables'][$table]))
            throw new \InvalidArgumentException("Invalid table name: $table");

        // Check (and get them) if columns are set for the given table
        if (isset($validation['database']['tables'][$table]['columns'])) {

            $this->table = $table;
            $this->validColumns = $validation['database']['tables'][$table]['columns'];
        } else {

            throw new \InvalidArgumentException("No columns defined for table: $table");
        }
    }

    /**
     * The  `findAll` method retrieves all rows from a database table and returns them as an
     * associative array.
     * 
     * @return array Associative array of all rows from the specified table, possibly empty.
     */
    public function findAll(): array {

        try {

            $stmt = $this->dbConnection->query("SELECT * FROM {$this->table}");

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {

            throw $e;
        }
    }

    /**
     * The 'findByColumnValue' method searches for rows in a database table where a specific column
     * matches a given value.
     * 
     * @param string $column Column name in the database table to run the search on.
     * @param string $value Expression to search for in the specified column.
     * 
     * @return array Associative array of all rows from the specified table, possibly empty.
     */
    public function findByColumnValue(string $column, string $value): array {

        try {

            // Validate column name against the list of the tables columns.
            if (!in_array($column, $this->validColumns))
                throw new \InvalidArgumentException("Invalid column name: $column");

            $stmt = $this->dbConnection->prepare("SELECT * FROM {$this->table} WHERE $column LIKE :value");
            $stmt->execute(['value' => '%' . $value . '%']);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * The 'insert' method inserts data from an entity object into a database table using prepared statements.
     * 
     * @param Entity $entity Object that implements the 'Entity' interface. 
     * This interface defines a method for converting the entity object into an array representation. 
     * It's 'toArray' method is used to retrieve the data from the entity object converted to an array ready to insert.
     * 
     * @return bool Success of the operation.
     */
    public function insert(BaseEntity $entity): bool {

        try {
            $data = $entity->toArray();

            $fields = implode(", ", array_keys($data));
            $placeholders = ":" . implode(", :", array_keys($data));

            $stmt = $this->dbConnection->prepare("INSERT INTO {$this->table} ($fields) VALUES ($placeholders)");
            return $stmt->execute($data);
        } catch (\Exception $e) {

            throw $e;
        }
    }

    /**
     * The 'update' method updates a record in a database table with the given entity data.
     * 
     * @param int $id Unique identifier of the record to be updated in the database table.
     * @param Entity $entity Object that implements the 'Entity' interface. 
     * This interface defines a method for converting the entity object into an array representation. 
     * It's 'toArray' method is used to retrieve the data from the entity object converted to an array ready to update.
     * 
     * @return bool Success of the operation.
     */
    public function update(int $id, BaseEntity $entity): bool {

        try {
            $data = $entity->toArray();

            $setFields = implode(", ", array_map(function ($key) {
                return "$key = :$key";
            }, array_keys($data)));

            $stmt = $this->dbConnection->prepare("UPDATE {$this->table} SET $setFields WHERE id = :id");
            $data['id'] = $id;
            return $stmt->execute($data);
        } catch (\Exception $e) {

            throw $e;
        }
    }

    /**
     * The 'delete' method deletes a record from a database table based on the provided id.
     * 
     * @param int $id Unique identifier of the record to be updated in the database table.
     * 
     * @return bool Success of the operation.
     */
    public function delete(int $id): bool {

        try {

            $stmt = $this->dbConnection->prepare("DELETE FROM {$this->table} WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (\Exception $e) {

            throw $e;
        }
    }
}

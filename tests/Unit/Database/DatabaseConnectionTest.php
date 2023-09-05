<?php

namespace Tests\Unit\Database;

use App\Database\DatabaseConnection;
use Codeception\Test\Unit;
use PDO;
use PDOException;

/**
 * The 'DatabaseConnectionTest' is a unit test class for testing the 'DatabaseConnection' class.
 * 
 * Tests the functionality of creating an instance of a PDO object  
 * and getting the connection to the required database.
 */
class DatabaseConnectionTest extends Unit {

    protected $tester;
    private $databaseConnection;

    /**
     * Sets up the necessary environment for running tests by 
     * creating a 'DatabaseConnection' class.
     */
    protected function _before() {

        $this->databaseConnection = new DatabaseConnection('host', 'database', 'user', 'password');
    }


    /**
     * Tests whether the 'getConnection' method of the 'DatabaseConnection' class 
     * returns a PDO instance when using PDO's own database.
     */
    public function testGetConnectionReturnsPdoInstance() {

        // Assert that the returned object is a PDO instance
        $this->assertInstanceOf(PDO::class, $this->databaseConnection->getConnection('sqlite::memory:'));
    }

    /**
     * Tests whether the 'getConnection' method of the 'DatabaseConnection' class 
     * throws a PDO exception with the correct message when using invalid parameters.
     */
    public function testGetConnectionThrowsExceptionOnProperty() {

        // Assert that an Exception is thrown with correct message
        $this->expectException(PDOException::class);
        $this->expectExceptionMessageMatches('/getaddrinfo for host failed/');

        // Act 
        $this->databaseConnection->getConnection();
    }

    /**
     * Tests whether the 'getConnection' method of the 'DatabaseConnection' class 
     * thorows a PDO exception with the correct message when using invalid parameters.
     */
    public function testGetConnectionThrowsExceptionOnDSN() {

        // Assert that an Exception is thrown with correct message
        $this->expectException(PDOException::class);
        $this->expectExceptionMessageMatches('/Argument #1 \(\\$dsn\) must be a valid data source name/');

        // Act 
        $this->databaseConnection->getConnection('invalidDSN');
    }
}

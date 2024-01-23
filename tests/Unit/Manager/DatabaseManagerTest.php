<?php

namespace Tests\Unit\Manager;

use App\Manager\DatabaseManager;
use Codeception\Test\Unit;
use PDO;
use PDOException;

/**
 * The 'DatabaseManagerTest' is a unit test class for testing the 'DatabaseManager' class.
 * 
 * Tests the functionality of creating an instance of a PDO object  
 * and getting the connection to the required database.
 */
class DatabaseManagerTest extends Unit {

    protected $tester;
    private $dbManager;

    /**
     * Sets up the necessary environment for running tests by 
     * creating a 'DatabaseManager' class.
     */
    protected function _before() {

        $this->dbManager = new DatabaseManager('host', 'database', 'user', 'password');
    }

    /**
     * Tests whether the 'getConnection' method of the 'DatabaseManager' class 
     * returns the correct name.
     */
    public function testDatabaseNameIsCorrect() {

        // Assert that the returned string equals the expected one.
        $this->assertEquals('database', $this->dbManager->getDatabaseName());
    }

    /**
     * Tests whether the 'getConnection' method of the 'DatabaseManager' class 
     * returns a PDO instance when using PDO's own database.
     */
    public function testGetConnectionReturnsPdoInstance() {

        // Assert that the returned object is a PDO instance
        $this->assertInstanceOf(PDO::class, $this->dbManager->getConnection('sqlite::memory:'));
    }

    /**
     * Tests whether the 'getConnection' method of the 'DatabaseManager' class 
     * throws a PDO exception with the correct message when using invalid parameters.
     */
    public function testGetConnectionThrowsExceptionOnProperty() {

        // Assert that an Exception is thrown with correct message
        $this->expectException(PDOException::class);
        $this->expectExceptionMessageMatches('/getaddrinfo for host failed/');

        // Act 
        $this->dbManager->getConnection();
    }

    /**
     * Tests whether the 'getConnection' method of the 'DatabaseManager' class 
     * thorows a PDO exception with the correct message when using invalid parameters.
     */
    public function testGetConnectionThrowsExceptionOnDSN() {

        // Assert that an Exception is thrown with correct message
        $this->expectException(PDOException::class);
        $this->expectExceptionMessageMatches('/Argument #1 \(\\$dsn\) must be a valid data source name/');

        // Act 
        $this->dbManager->getConnection('invalidDSN');
    }
}

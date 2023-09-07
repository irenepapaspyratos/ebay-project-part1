<?php

namespace App\Database;

use PDO;
use PDOException;

class DatabaseConnection {

    private string $host;
    private string $dbname;
    private string $username;
    private string $password;
    private string $charset;
    private array $options;

    /**
     * The '__construct' method initializes properties with corresponding values, either defaults or passed as arguments.
     * 
     * Initializes the properties for the database connection.
     *
     * @param string $host Address of the database server.
     * @param string $dbname Name of the database.
     * @param string $username Database user.
     * @param string $password Database user password.
     * @param string $charset Character set for the connection. Default: 'utf8mb4'.
     * @param array $options PDO connection options.
     * 
     * @return void
     */
    public function __construct(
        string $host,
        string $dbname,
        string $username,
        string $password,
        string $charset = 'utf8mb4',
        array $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ],
    ) {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
        $this->charset = $charset;
        $this->options = $options;
    }

    /**
     * The 'getConnection' method returns a PDO object for connecting to a database.
     * 
     * 
     * @param string $dsn Includes information about the protocol, host, database name and charset.
     * If not set, it defaults to the protocol for a MySQL or MariaDB database
     * with the given properties 'host', 'dbName' and 'charset'.
     * 
     * @return PDO Represents the connection to a database.
     * @throws PDOException When unable to connect to the database.
     */
    public function getConnection(?string $dsn = null): PDO {

        // Assign default Data Source Name
        $dsn = $dsn ?:  "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";

        try {

            return new PDO($dsn, $this->username, $this->password, $this->options);
        } catch (PDOException $e) {

            throw $e;
        }
    }
}

<?php

namespace Tests\Unit\Utility;

use App\Utility\CustomLogger;
use Codeception\Test\Unit;

/**
 * The 'CustomLoggerTest' is a unit test class for the 'CustomLogger' class. 
 * 
 * Tests the functionality of writing error and info messages to the correct log files. 
 */
class CustomLoggerTest extends Unit {

    protected $tester;
    private $customLogger;
    private $errorLogPath;
    private $infoLogPath;
    private $nonWritableLogPath;

    /**
     * Sets up the necessary environment for running tests by cleaning up the test directory, 
     * defining paths for writable test log files and 
     * creating a non-writable test log file.
     */
    protected function _before() {

        // Clean up the directory of the test files
        $this->tester->cleanDir(codecept_data_dir());

        // Define the temporary paths for writable test log files
        $this->errorLogPath = codecept_data_dir('error.log');
        $this->infoLogPath = codecept_data_dir('info.log');

        // Define the temporary path for a non-writable test log file and set permissions to read-only simulating a non-writable file
        $this->nonWritableLogPath = codecept_data_dir('non_writable.log');
        touch($this->nonWritableLogPath);
        chmod($this->nonWritableLogPath, 0444);
    }

    /**
     * Cleans the codecept_data_dir directory after each test by deleting all files.
     */
    protected function _after() {

        $this->tester->cleanDir(codecept_data_dir());
    }

    /**
     * Tests the 'errorLog' method of the 'CustomLogger' class 
     * whether an error message is successfully written to the error log file 
     * and the info log file does not exist.
     */
    public function testErrorLogWritesToCorrectFile() {

        // Create a new 'CustomLogger' instance with test log files
        $this->customLogger = new CustomLogger($this->errorLogPath, $this->infoLogPath);

        // Arrange
        $message = 'Test error message';

        // Act
        $this->customLogger->errorLog($message);

        // Assert that the $result is true, meaning that the error message was successfully written to the error log 
        $this->tester->seeFileFound($this->errorLogPath);
        $this->tester->seeInThisFile($message);

        // Assert that the info file still is not existing
        $this->assertFalse(file_exists($this->infoLogPath));
    }

    /**
     * Tests whether the 'errorLog' method of the 'CustomLogger' class throws a
     * '\Codeception\Exception\Warning' exception when it cannot write to a log file.
     */
    public function testErrorLogCannotWriteToFile() {

        // Create a new 'CustomLogger' instance with test log files
        $this->customLogger = new CustomLogger($this->nonWritableLogPath, $this->infoLogPath);

        // Arrange
        $message = 'Test error message';

        // Assert that an exception of type '\Codeception\Exception\Warning' is thrown
        $this->expectException(\Codeception\Exception\Warning::class);

        // Act
        $this->customLogger->errorLog($message);
    }

    /**
     * Tests the 'infoLog' method of the 'CustomLogger' class whether 
     * an informational message is successfully written to the info log file 
     * and the error log file does not exist.
     */
    public function testInfoLogWritesToCorrectFile() {

        // Create a new 'CustomLogger' instance with test log files
        $this->customLogger = new CustomLogger($this->errorLogPath, $this->infoLogPath);

        // Arrange
        $message = 'Test info message';

        // Act
        $this->customLogger->infoLog($message);

        // Assert that the $result is true, meaning that the error message was successfully written to the info log 
        $this->tester->seeFileFound($this->infoLogPath);
        $this->tester->seeInThisFile($message);

        // Assert that the error file still is not existing
        $this->assertFalse(file_exists($this->errorLogPath));
    }

    /**
     * Tests whether the 'infoLog' method of the 'CustomLogger' class throws a
     * '\Codeception\Exception\Warning' exception when it cannot write to a log file.
     */
    public function testInfoLogCannotWriteToFile() {

        // Create a new 'CustomLogger' instance with test log files
        $this->customLogger = new CustomLogger($this->errorLogPath, $this->nonWritableLogPath);

        // Arrange
        $message = 'Test info message';

        // Assert that an exception of type '\Codeception\Exception\Warning' is thrown
        $this->expectException(\Codeception\Exception\Warning::class);

        // Act
        $this->customLogger->infoLog($message);
    }
}

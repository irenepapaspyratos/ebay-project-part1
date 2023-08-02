<?php

namespace Tests\Unit;

use App\Utility\CustomLogger;
use Codeception\Test\Unit;

/**
 * The 'CustomLoggerTest' is a unit test class for the 'CustomLogger' class 
 * 
 * It tests the functionality of writing error and info messages to the correct log files. 
 */
class CustomLoggerTest extends Unit {

    protected $tester;
    private $customLogger;
    private $errorLogPath;
    private $infoLogPath;
    private $nonWritableLogPath;

    /**
     * The '_before' function is used to set up the necessary environment for running tests
     * 
     * In this case it is deleting all files in the test directory, 
     * defining paths for writable/non-writable log files 
     * and setting permissions for the non-writable log file.
     */
    protected function _before() {

        // Clean up the directory of the test files (just in case...)
        $this->tester->cleanDir(codecept_data_dir());

        // Define the temporary paths for writable test log files
        $this->errorLogPath = codecept_data_dir('error.log');
        $this->infoLogPath = codecept_data_dir('info.log');

        // Define the temporary path for a non-writable test log file and deny permission
        $this->nonWritableLogPath = codecept_data_dir('non_writable.log');
        touch($this->nonWritableLogPath);
        chmod($this->nonWritableLogPath, 0444);
    }

    /**
     * The '_after' function is used to clean up the test environment after running tests
     * 
     * In this case it deletes all files in the test directory
     */
    protected function _after() {

        $this->tester->cleanDir(codecept_data_dir());
    }

    /**
     * The 'testErrorLogWritesToCorrectFile' function tests the 'errorLog' method
     * 
     * It tests wether an error message is written to the specified error file after creating it  
     * as well as that the specified log file is not created, as both are non-existent before.
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
     * The 'testErrorLogCannotWriteToFile' function tests the 'errorLog' method
     * 
     * It tests wether an error message can be written to a file with missing permissions
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
     * The 'testInfoLogWritesToCorrectFile' function tests the 'infoLog' method
     * 
     * It tests wether an info message is written to the specified info file after creating it 
     * as well as that the specified error file is not created, as both are non-existent before.
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
     * The 'testInfoLogCannotWriteToFile' function tests the 'infoLog' method
     * 
     * It tests wether an info message can be written to a file with missing permissions
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

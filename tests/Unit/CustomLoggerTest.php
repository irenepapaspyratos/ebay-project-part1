<?php

namespace Tests\Unit;

use App\Utility\CustomLogger;
use Codeception\Test\Unit;

/**
 * The CustomLoggerTest class is a unit test class that tests the functionality of the CustomLogger class 
 * in writing error and info messages to the correct log files. 
 */
class CustomLoggerTest extends Unit {

    protected $tester;
    private $customLogger;
    private $errorLogPath;
    private $infoLogPath;
    private $nonWritableLogPath;

    /**
     * The function initializes the paths for log files 
     * and creates a new instance of the CustomLogger class to be tested.
     */
    protected function _before() {

        // Clean up all test files (just in case...)
        $this->tester->cleanDir(codecept_data_dir());

        // Define the temporary paths for writable test log files
        $this->errorLogPath = codecept_data_dir('error.log');
        $this->infoLogPath = codecept_data_dir('info.log');

        // Define the temporary path for a non-writable test log file
        $this->nonWritableLogPath = codecept_data_dir('non_writable.log');
        touch($this->nonWritableLogPath);
        chmod($this->nonWritableLogPath, 0444);
    }

    /**
     * The function cleans up all test files by deleting them after each test.
     */
    protected function _after() {

        $this->tester->cleanDir(codecept_data_dir());
    }

    /**
     * The function tests if an error message is written to the correct file.
     */
    public function testErrorLogWritesToCorrectFile() {

        // Create a new CustomLogger instance with test log files
        $this->customLogger = new CustomLogger($this->errorLogPath, $this->infoLogPath);

        // Arrange
        $message = 'Test error message';

        // Act
        $this->customLogger->error_log($message);

        // Assert that the $result is true, meaning that the error message was successfully written to the error log 
        $this->tester->seeFileFound($this->errorLogPath);
        $this->tester->seeInThisFile($message);

        // Assert that the info file still is not existing
        $this->assertFalse(file_exists($this->infoLogPath));
    }

    /**
     * The function tests if an info message is written to the correct file.
     */
    public function testInfoLogWritesToCorrectFile() {

        // Create a new CustomLogger instance with test log files
        $this->customLogger = new CustomLogger($this->errorLogPath, $this->infoLogPath);

        // Arrange
        $message = 'Test info message';

        // Act
        $this->customLogger->info_log($message);

        // Assert that the $result is true, meaning that the error message was successfully written to the info log 
        $this->tester->seeFileFound($this->infoLogPath);
        $this->tester->seeInThisFile($message);

        // Assert that the error file still is not existing
        $this->assertFalse(file_exists($this->errorLogPath));
    }

    /**
     * The function tests if an error message cannot be written to a file with missing permissions
     */
    public function testErrorLogCannotWriteToFile() {

        // Create a new CustomLogger instance with test log files
        $this->customLogger = new CustomLogger($this->nonWritableLogPath, $this->infoLogPath);

        // Arrange
        $message = 'Test error message';

        // Assert that an exception of type \Codeception\Exception\Warning is thrown
        $this->expectException(\Codeception\Exception\Warning::class);

        // Act
        $this->customLogger->error_log($message);
    }

    /**
     * The function tests if an info message cannot be written to a file with missing permissions
     */
    public function testInfoLogCannotWriteToFile() {

        // Create a new CustomLogger instance with test log files
        $this->customLogger = new CustomLogger($this->errorLogPath, $this->nonWritableLogPath);

        // Arrange
        $message = 'Test info message';

        // Assert that an exception of type \Codeception\Exception\Warning is thrown
        $this->expectException(\Codeception\Exception\Warning::class);

        // Act
        $this->customLogger->info_log($message);
    }
}

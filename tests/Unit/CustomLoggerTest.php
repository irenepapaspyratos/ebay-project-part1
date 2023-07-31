<?php

namespace Tests\Unit;

use App\Utility\CustomLogger;
use Codeception\Test\Unit;

class CustomLoggerTest extends Unit {

    protected $tester;
    private $errorLogPath;
    private $infoLogPath;
    private $customLogger;

    protected function _before() {

        // Define the paths for the log files
        $this->errorLogPath = codecept_data_dir('error.log');
        $this->infoLogPath = codecept_data_dir('info.log');

        // Create a new CustomLogger instance
        $this->customLogger = new CustomLogger($this->errorLogPath, $this->infoLogPath);
    }

    protected function _after() {

        // Clean up the log files after each test
        if (file_exists($this->errorLogPath)) {
            $this->tester->deleteFile($this->errorLogPath);
        }
        if (file_exists($this->infoLogPath)) {
            $this->tester->deleteFile($this->infoLogPath);
        }
    }

    /**
     * The function tests if an error message is written to the correct file.
     */
    public function testErrorLogWritesToCorrectFile() {

        // Arrange
        $message = 'Test error message';

        // Act
        $this->customLogger->error_log($message);

        // Assert
        $this->tester->seeFileFound($this->errorLogPath);
        $this->tester->openFile($this->errorLogPath);
        $this->tester->seeInThisFile($message);
    }

    /**
     * The function tests if an info message is written to the correct file.
     */
    public function testInfoLogWritesToCorrectFile() {

        // Arrange
        $message = 'Test info message';

        // Act
        $this->customLogger->info_log($message);

        // Assert
        $this->tester->seeFileFound($this->infoLogPath);
        $this->tester->openFile($this->infoLogPath);
        $this->tester->seeInThisFile($message);
    }
}

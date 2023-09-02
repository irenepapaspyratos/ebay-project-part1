<?php

namespace App\Utility;

/**
 * The 'CustomLogger' class allows logging of error and info messages to separate, customized log files.
 */
class CustomLogger {

    private string $infoLogPath;
    private string $errorLogPath;

    /**
     * The '__construct' method initializes properties with corresponding values, either defaults or passed as arguments.
     * 
     * The errorLogPath and infoLogPath are set to the provided paths.
     * 
     * @param string $errorLogPath File path where the error messages will be logged.
     * @param string $infoLogPath File path where informational messages will be logged. 
     * 
     * @return void
     */
    public function __construct(string $infoLogPath, string $errorLogPath) {
        $this->infoLogPath = $infoLogPath;
        $this->errorLogPath = $errorLogPath;
    }

    /**
     * The 'log' method appends a formatted message with a UTC+0 timestamp in ISO 8601 format to a specified file.
     * 
     * @param string $message Message to be written to the log file.
     * @param string $filePath Path of the file where the message will be logged.
     * 
     * @return void
     */
    private function log(string $message, string $filePath): void {
        $date = gmdate('Y-m-d\TH:i:s.v\Z');
        $message = "[{$date}] {$message}" . PHP_EOL;
        file_put_contents($filePath, $message, FILE_APPEND);
    }

    /**
     * The 'errorLog' method logs an error message to a specified error log file.
     * 
     * @param string $message Error message that needs to be logged.
     * 
     * @return void
     */
    public function errorLog(string $message): void {
        $this->log($message, $this->errorLogPath);
    }

    /**
     * The 'infoLog' method logs an error message to a specified info log file.
     * 
     * @param string $message Informational message that needs to be logged.
     * 
     * @return void
     */
    public function infoLog(string $message): void {
        $this->log($message, $this->infoLogPath);
    }
}

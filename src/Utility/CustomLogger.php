<?php

namespace App\Utility;

class CustomLogger {
    private $errorLogPath;
    private $infoLogPath;

    public function __construct(string $errorLogPath = __DIR__ . '/../../log/error.log', string $infoLogPath = __DIR__ . '/../../log/info.log') {
        $this->errorLogPath = $errorLogPath;
        $this->infoLogPath = $infoLogPath;
    }

    private function log(string $message, string $filePath): void {
        $date = gmdate('Y-m-d\TH:i:s.v\Z');
        $message = "[{$date}] {$message}" . PHP_EOL;
        file_put_contents($filePath, $message, FILE_APPEND);
    }

    public function error_log(string $message): void {
        $this->log($message, $this->errorLogPath);
    }

    public function info_log(string $message): void {
        $this->log($message, $this->infoLogPath);
    }
}

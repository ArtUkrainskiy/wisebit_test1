<?php

namespace user\infrastructure\logger;

use user\app\logger\ChangeLoggerInterface;

class FileChangeLogger implements ChangeLoggerInterface
{
    private string $logFilePath;

    public function __construct(string $logFilePath)
    {
        $this->logFilePath = $logFilePath;
    }

    /**
     * @param string $description
     * @param array $data
     * @throws \JsonException
     */
    public function logChange(string $description, array $data): void
    {
        $record = [
            'description' => $description,
            'data'        => $data,
            'timestamp'   => date('Y-m-d H:i:s')
        ];

        file_put_contents(
            $this->logFilePath,
            json_encode($record, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE) . PHP_EOL,
            FILE_APPEND
        );
    }
}

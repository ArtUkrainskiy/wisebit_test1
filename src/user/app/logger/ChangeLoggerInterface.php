<?php

namespace user\app\logger;

interface ChangeLoggerInterface
{
    /**
     * Logs a change event
     *
     * @param string $description
     * @param array  $data
     * @return void
     */
    public function logChange(string $description, array $data): void;
}

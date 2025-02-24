<?php

namespace user\tests\unit\application\logger;

use PHPUnit\Framework\TestCase;
use user\infrastructure\logger\FileChangeLogger;

/**
 * Tests for FileChangeLogger
 */
class FileChangeLoggerTest extends TestCase
{
    private string $logFile;

    protected function setUp(): void
    {
        parent::setUp();
        $this->logFile = __DIR__ . '/test_changes.log';
        if (file_exists($this->logFile)) {
            unlink($this->logFile);
        }
    }

    protected function tearDown(): void
    {
        if (file_exists($this->logFile)) {
            unlink($this->logFile);
        }
        parent::tearDown();
    }

    public function testLogChange(): void
    {
        $logger = new FileChangeLogger($this->logFile);
        $logger->logChange('Test event', ['foo' => 'bar']);

        $this->assertFileExists($this->logFile);

        $lines = file($this->logFile, FILE_IGNORE_NEW_LINES);
        $this->assertCount(1, $lines);

        $decoded = json_decode($lines[0], true);
        $this->assertArrayHasKey('description', $decoded);
        $this->assertArrayHasKey('data', $decoded);
        $this->assertSame('Test event', $decoded['description']);
        $this->assertSame(['foo' => 'bar'], $decoded['data']);
    }
}

<?php

namespace user\tests\unit\domain\validation;

use PHPUnit\Framework\TestCase;
use user\app\validator\StopWordsFilter;

/**
 * Tests for StopWordsFilter
 */
class StopWordsFilterTest extends TestCase
{
    public function testHasBannedWordsTrue(): void
    {
        $filter = new StopWordsFilter(['admin', 'root']);
        $this->assertTrue($filter->hasBannedWords('myadminuser'));
        $this->assertTrue($filter->hasBannedWords('rootstuff'));
    }

    public function testHasBannedWordsFalse(): void
    {
        $filter = new StopWordsFilter(['admin', 'root']);
        $this->assertFalse($filter->hasBannedWords('john1234'));
        $this->assertFalse($filter->hasBannedWords('user'));
    }
}

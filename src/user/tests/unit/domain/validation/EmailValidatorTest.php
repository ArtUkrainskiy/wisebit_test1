<?php

namespace user\tests\unit\domain\validation;

use PHPUnit\Framework\TestCase;
use user\app\validator\EmailValidator;
use DomainException;

/**
 * Tests for EmailValidator
 */
class EmailValidatorTest extends TestCase
{
    public function testValidateNoBannedDomains(): void
    {
        $validator = new EmailValidator();

        $validator->validate('user@example.org');
        $this->assertTrue(true);
    }

    public function testValidateBannedDomain(): void
    {
        $validator = new EmailValidator(['spam.com']);
        $this->expectException(DomainException::class);
        $validator->validate('user@spam.com');
    }
}

<?php

namespace user\tests\unit\domain\validation;

use PHPUnit\Framework\TestCase;
use DomainException;
use DateTime;
use user\app\validator\UserValidator;
use user\app\validator\EmailValidator;
use user\app\validator\StopWordsFilter;

/**
 * Tests for UserValidator
 */
class UserValidatorTest extends TestCase
{
    private UserValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();

        $stopWordsFilter = new StopWordsFilter(['admin', 'root']);
        $emailValidator = new EmailValidator(['spam.com']);

        $this->validator = new UserValidator($emailValidator, $stopWordsFilter);
    }

    public function testValidateNameSuccess(): void
    {
        $this->validator->validateName('john1234');
        $this->assertTrue(true, 'No exception thrown => success.');
    }

    public function testValidateNameFailsIfShort(): void
    {
        $this->expectException(DomainException::class);
        $this->validator->validateName('abc12');
    }

    public function testValidateNameFailsIfContainsBadSymbols(): void
    {
        $this->expectException(DomainException::class);
        $this->validator->validateName('invalid_name');
    }

    public function testValidateNameFailsIfContainsBannedWord(): void
    {
        $this->expectException(DomainException::class);
        $this->validator->validateName('myadminuser');
    }

    public function testValidateEmailSuccess(): void
    {
        // Should pass
        $this->validator->validateEmail('test@example.com');
        $this->assertTrue(true);
    }

    public function testValidateEmailFailsBadFormat(): void
    {
        $this->expectException(DomainException::class);
        $this->validator->validateEmail('not-an-email');
    }

    public function testValidateEmailFailsBannedDomain(): void
    {
        $this->expectException(DomainException::class);
        $this->validator->validateEmail('john@spam.com');
    }

    public function testValidateDeletedDateSuccess(): void
    {
        $created = new DateTime('2020-01-01 10:00:00');
        $deleted = new DateTime('2020-01-02 12:00:00');
        $this->validator->validateDeletedDate($created, $deleted);
        $this->assertTrue(true);
    }

    public function testValidateDeletedDateFails(): void
    {
        $this->expectException(DomainException::class);

        $created = new DateTime('2020-01-02 10:00:00');
        $deleted = new DateTime('2020-01-01 12:00:00');
        $this->validator->validateDeletedDate($created, $deleted);
    }
}

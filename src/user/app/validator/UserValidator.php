<?php

namespace user\app\validator;

use user\domain\validator\UserValidatorInterface;
use DateTimeInterface;
use DomainException;

/**
 * Concrete implementation of UserValidatorInterface
 */
class UserValidator implements UserValidatorInterface
{
    private EmailValidator $emailValidator;
    private StopWordsFilter $stopWordsFilter;

    public function __construct(
        EmailValidator $emailValidator,
        StopWordsFilter $stopWordsFilter
    ) {
        $this->emailValidator = $emailValidator;
        $this->stopWordsFilter = $stopWordsFilter;
    }

    public function validateName(string $name): void
    {
        // Only a-z, 0-9
        if (!preg_match('/^[a-z0-9]+$/', $name)) {
            throw new DomainException("Имя пользователя может содержать только a-z и 0-9.");
        }

        // Minimum length is 8
        if (mb_strlen($name) < 8) {
            throw new DomainException("Имя пользователя не может быть короче 8 символов.");
        }

        // Check if it contains banned words
        $this->validateStopWords($name);
    }

    public function validateEmail(string $email): void
    {
        // Basic format check
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new DomainException("Некорректный формат e-mail: $email");
        }

        // Additional domain logic
        $this->emailValidator->validate($email);
    }

    public function validateStopWords(string $text): void
    {
        if ($this->stopWordsFilter->hasBannedWords($text)) {
            throw new DomainException("Текст содержит запрещённое слово: $text");
        }
    }

    public function validateDeletedDate(DateTimeInterface $created, DateTimeInterface $deleted): void
    {
        if ($deleted < $created) {
            throw new DomainException("Дата удаления не может быть раньше даты создания.");
        }
    }
}

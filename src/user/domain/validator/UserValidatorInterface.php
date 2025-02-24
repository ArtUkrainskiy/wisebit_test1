<?php

namespace user\domain\validator;

use DateTimeInterface;
use DomainException;

interface UserValidatorInterface
{
    /**
     * @param string $name
     * @throws DomainException
     */
    public function validateName(string $name): void;

    /**
     * @param string $email
     * @throws DomainException
     */
    public function validateEmail(string $email): void;

    /**
     * @param string $text
     * @throws DomainException
     */
    public function validateStopWords(string $text): void;

    /**
     * @param DateTimeInterface $created
     * @param DateTimeInterface $deleted
     * @throws DomainException
     */
    public function validateDeletedDate(DateTimeInterface $created, DateTimeInterface $deleted): void;
}

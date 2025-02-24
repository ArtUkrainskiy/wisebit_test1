<?php

namespace user\app\validator;

use DomainException;

class EmailValidator
{
    private array $bannedDomains;

    public function __construct(array $bannedDomains = [])
    {
        $this->bannedDomains = $bannedDomains;
    }

    /**
     * Validates email domain against banned domains
     *
     * @param string $email
     * @throws DomainException
     */
    public function validate(string $email): void
    {
        $domain = mb_strtolower(explode('@', $email)[1] ?? '');
        if (in_array($domain, $this->bannedDomains, true)) {
            throw new DomainException("Домен '$domain' находится в списке запрещённых.");
        }
    }
}

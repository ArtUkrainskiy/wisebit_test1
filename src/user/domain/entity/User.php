<?php

namespace user\domain\entity;

use DateTime;

class User
{
    private int $id;
    private string $name;
    private string $email;
    private DateTime $created;
    private ?DateTime $deleted;
    private ?string $notes;

    public function __construct(
        int $id,
        string $name,
        string $email,
        DateTime $created,
        ?DateTime $deleted,
        ?string $notes
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->created = $created;
        $this->deleted = $deleted;
        $this->notes = $notes;
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCreated(): DateTime
    {
        return $this->created;
    }

    public function getDeleted(): ?DateTime
    {
        return $this->deleted;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    // Setters
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setDeleted(?DateTime $deleted): void
    {
        $this->deleted = $deleted;
    }

    public function setNotes(?string $notes): void
    {
        $this->notes = $notes;
    }
}

<?php

namespace user\app\service;

use user\domain\repository\UserRepositoryInterface;
use user\domain\validator\UserValidatorInterface;
use user\domain\entity\User;
use user\app\logger\ChangeLoggerInterface;
use DateTime;
use InvalidArgumentException;

class UserService
{
    private UserRepositoryInterface $userRepository;
    private UserValidatorInterface $userValidator;
    private ChangeLoggerInterface $changeLogger;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UserValidatorInterface  $userValidator,
        ChangeLoggerInterface   $changeLogger
    )
    {
        $this->userRepository = $userRepository;
        $this->userValidator = $userValidator;
        $this->changeLogger = $changeLogger;
    }

    /**
     * Creates a new user
     *
     * @param string $name
     * @param string $email
     * @param string|null $notes
     * @return User
     */
    public function createUser(string $name, string $email, ?string $notes = null): User
    {
        // Validate name, email
        $this->userValidator->validateName($name);
        $this->userValidator->validateEmail($email);

        // Check if name or email exist
        if ($this->userRepository->existsByName($name)) {
            throw new InvalidArgumentException("Пользователь с именем '$name' уже существует!");
        }
        if ($this->userRepository->existsByEmail($email)) {
            throw new InvalidArgumentException("Пользователь с email '$email' уже существует!");
        }

        $user = new User(
            0,
            $name,
            $email,
            new DateTime(),
            null,
            $notes
        );

        $saved = $this->userRepository->create($user);

        $this->changeLogger->logChange('Создание пользователя', [
            'id' => $saved->getId(),
            'name' => $saved->getName(),
            'email' => $saved->getEmail(),
        ]);

        return $saved;
    }

    /**
     * Updates an existing user
     *
     * @param int $userId
     * @param string|null $newName
     * @param string|null $newEmail
     * @param DateTime|null $deleted
     * @param string|null $notes
     * @return User
     */
    public function updateUser(
        int       $userId,
        ?string   $newName,
        ?string   $newEmail,
        ?DateTime $deleted,
        ?string   $notes
    ): User
    {
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            throw new InvalidArgumentException("Пользователь с ID={$userId} не найден");
        }

        // Name
        if ($newName !== null) {
            $this->userValidator->validateName($newName);
            if ($this->userRepository->existsByName($newName) && $newName !== $user->getName()) {
                throw new InvalidArgumentException("Пользователь с именем '$newName' уже существует!");
            }
            $user->setName($newName);
        }

        // Email
        if ($newEmail !== null) {
            $this->userValidator->validateEmail($newEmail);
            if ($this->userRepository->existsByEmail($newEmail) && $newEmail !== $user->getEmail()) {
                throw new InvalidArgumentException("Пользователь с email '$newEmail' уже существует!");
            }
            $user->setEmail($newEmail);
        }

        // Deleted date
        if ($deleted !== null) {
            $this->userValidator->validateDeletedDate($user->getCreated(), $deleted);
            $user->setDeleted($deleted);
        }

        if ($notes !== null) {
            $user->setNotes($notes);
        }

        $updated = $this->userRepository->update($user);

        $this->changeLogger->logChange('Обновление пользователя', [
            'id' => $updated->getId(),
            'name' => $updated->getName(),
            'email' => $updated->getEmail(),
            'deleted' => $updated->getDeleted()?->format('Y-m-d H:i:s'),
        ]);

        return $updated;
    }
}

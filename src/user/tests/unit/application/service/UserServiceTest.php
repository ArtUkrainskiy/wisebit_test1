<?php

namespace user\tests\unit\application\service;

use PHPUnit\Framework\TestCase;
use user\app\service\UserService;
use user\domain\repository\UserRepositoryInterface;
use user\domain\entity\User;
use user\domain\validator\UserValidatorInterface;
use user\app\logger\ChangeLoggerInterface;
use DateTime;
use InvalidArgumentException;

/**
 * Tests for UserService
 */
class UserServiceTest extends TestCase
{
    private UserRepositoryInterface $repo;
    private UserValidatorInterface $validator;
    private ChangeLoggerInterface $logger;
    private UserService $service;

    protected function setUp(): void
    {
        parent::setUp();

        // Mocks
        $this->repo = $this->createMock(UserRepositoryInterface::class);
        $this->validator = $this->createMock(UserValidatorInterface::class);
        $this->logger = $this->createMock(ChangeLoggerInterface::class);

        $this->service = new UserService($this->repo, $this->validator, $this->logger);
    }

    public function testCreateUserSuccess(): void
    {
        $this->repo->method('existsByName')->willReturn(false);
        $this->repo->method('existsByEmail')->willReturn(false);

        $this->repo->method('create')->willReturnCallback(function(User $u) {
            return new User(
                10,
                $u->getName(),
                $u->getEmail(),
                $u->getCreated(),
                $u->getDeleted(),
                $u->getNotes()
            );
        });

        $this->logger->expects($this->once())
            ->method('logChange')
            ->with('Создание пользователя', $this->anything());

        $newUser = $this->service->createUser('john1234', 'john@example.com');

        $this->assertSame(10, $newUser->getId());
        $this->assertSame('john1234', $newUser->getName());
        $this->assertSame('john@example.com', $newUser->getEmail());
    }

    public function testCreateUserFailsIfNameExists(): void
    {
        $this->repo->method('existsByName')->willReturn(true);

        $this->expectException(InvalidArgumentException::class);
        $this->service->createUser('duplicateName', 'test@example.com');
    }

    public function testUpdateUserNotFound(): void
    {
        $this->repo->method('findById')->willReturn(null);

        $this->expectException(InvalidArgumentException::class);
        $this->service->updateUser(999, 'someName', null, null, null);
    }

    public function testUpdateUserSuccess(): void
    {
        // Existing user
        $existing = new User(5, 'oldName', 'old@example.com', new DateTime(), null, null);

        $this->repo->method('findById')->willReturn($existing);
        $this->repo->method('existsByName')->willReturn(false);
        $this->repo->method('existsByEmail')->willReturn(false);
        $this->repo->method('update')->willReturnCallback(fn(User $u) => $u);

        $this->logger->expects($this->once())
            ->method('logChange')
            ->with('Обновление пользователя', $this->anything());

        $updated = $this->service->updateUser(5, 'newName', 'new@example.com', null, 'notes');

        $this->assertSame(5, $updated->getId());
        $this->assertSame('newName', $updated->getName());
        $this->assertSame('new@example.com', $updated->getEmail());
        $this->assertSame('notes', $updated->getNotes());
    }
}

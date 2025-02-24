<?php

class UserEmailChangerService
{
    public function __construct(
        private readonly LockManager              $lockManager,
        private readonly UserEmailSenderInterface $emailSender,
        private readonly PDO                      $db
    ) {}

    /**
     * Изменяем email используя универсальный executeWithLock
     * @param int $userId
     * @param string $newEmail
     * @return void
     * @throws Throwable
     */
    public function changeEmail(int $userId, string $newEmail): void
    {
        $result = $this->lockManager->executeWithLock(
            'users',
            ['id' => $userId],
            function (array $userData) use ($newEmail) {
                if ($userData['email'] === $newEmail) {
                    return null;
                }

                $stmt = $this->db->prepare(
                    'UPDATE users SET email = :email WHERE id = :id'
                );
                $stmt->execute([
                    ':email' => $newEmail,
                    ':id'    => $userData['id']
                ]);

                return [
                    'oldEmail' => $userData['email'],
                    'newEmail' => $newEmail,
                ];
            }
        );

        if ($result !== null) {
            $this->emailSender->sendEmailChangedNotification($result['oldEmail'], $result['newEmail']);
        }
    }
}

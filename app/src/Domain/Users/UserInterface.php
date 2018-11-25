<?php declare(strict_types=1);

namespace App\Domain\Users;

use App\Domain\Users\User;

interface UserInterface
{
    public function getUser(int $userId): User;

    public function getByEmail(string $email): User;
}

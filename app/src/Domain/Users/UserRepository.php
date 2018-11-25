<?php declare(strict_types=1);

namespace App\Domain\Users;

use App\Domain\Users\User;

class UserRepository implements UserInterface
{
    /**
     * @var User
     */
    protected $model;
    /**
     * UserRepository constructor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Get a specific User
     *
     * @param int $userId
     *
     * @return User|null
     */
    public function getUser(int $userId): User
    {
        $user = $this->model->find($userId);
        return $this->returnUserInstanceOrNull($user);
    }
    
    /**
     * Get a user by email
     *
     * @param string $email
     *
     * @return User|null
     */
    public function getByEmail(string $email): User
    {
        $user = $this->model->where('email', $email)->first();
        return $this->returnUserInstanceOrNull($user);
    }
    
    /**
     * Return User instance or null
     *
     * @param $user
     *
     * @return User|null
     */
    private function returnUserInstanceOrNull($user): User
    {
        if ($user instanceof User) {
            return $user;
        }
        return null;
    }
}

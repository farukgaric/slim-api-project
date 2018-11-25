<?php declare(strict_types=1);

namespace App\Services;

use Firebase\JWT\JWT;
use App\Domain\Users\User;
use App\Domain\Users\UserInterface;

class Auth
{
    /**
     * @var JWT
     */
    private $jwt;
    /**
     * @var UserInterface
     */
    private $userRepository;

    /**
     * AuthService constructor.
     *
     * @param UserInterface $userRepository
     * @param JWT $jwt
     */
    public function __construct(UserInterface $userRepository, JWT $jwt)
    {
        $this->jwt = $jwt;
        $this->userRepository = $userRepository;
    }
    
    /**
     * Generate a random token
     *
     * @return string
     */
    public function generateToken(): string
    {
        return bin2hex(openssl_random_pseudo_bytes(16));
    }

    /**
     * Generate a JWT
     *
     * @param  JWT   $jwt
     * @param  array $data
     *
     * @return array
     */
    public function generateJWT(JWT $jwt, $data): array
    {
        $token = [
            'jti'  => base64_encode(random_bytes(32)),
            'iss'  => $_SERVER['APP_PROTOCOL'] . $_SERVER['APP_DOMAIN'],
            'aud'  => 'http://' . $_SERVER['CLIENT_URL'],
            'iat'  => time(),
            'nbf'  => time(),
            'exp'  => time() + 604800, // one week
            'data' => $data
        ];
        $jwt = $jwt->encode($token, $_SERVER['APP_SECRET'], 'HS512');
        return ['token' => $jwt];
    }

    /**
     * Get the decoded JWT
     *
     * @param  JWT    $jwt
     * @param  string $token
     *
     * @return array|bool
     */
    public function decodeJWT(JWT $jwt, $token)
    {
        try {
            $token = JWT::decode($token, $_SERVER['APP_SECRET'], ['HS512']);
            return $token;
        } catch (\Exception $e) {
            // log exception
            return false;
        }
    }

    /**
     * Hash user password
     *
     * @param  string $password
     *
     * @return string|bool
     */
    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT/*, $_SERVER['APP_PASS_ALGO_CONST']*/);
    }

    /**
     * Validate email and password
     *
     * @param  array  $input
     *
     * @return User|null
     */
    public function login(array $input)
    {
        if (!$user = $this->userRepository->getByEmail($input['email'])) {
            return null;
        }
        if (!password_verify($input['password'], $user->password)) {
            return null;
        }
        
        return $user;
    }
}

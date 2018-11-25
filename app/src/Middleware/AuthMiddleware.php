<?php declare(strict_types=1);

namespace App\Middleware;

use App\Domain\Users\UserInterface;
use App\Services\Auth;
use App\Services\Messages;
use Firebase\JWT\JWT;
use RKA\ContentTypeRenderer\Renderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Authentication using JWT
 *
 */
class AuthMiddleware
{
    /**
     * The default state to check authentication against
     *
     * @var string
     */
    protected $state = 'authenticated';

    /**
     * Create new Middleware service provider
     *
     * @param UserInterface $userRepository
     * @param Auth           $auth
     * @param Messages       $messages
     * @param UUID           $uuid
     * @param JWT            $jwt
     * @param Renderer       $renderer
     */
    public function __construct(UserInterface $userRepository, Auth $auth, Messages $messages, JWT $jwt, Renderer $renderer)
    {
        $this->userRepository = $userRepository;
        $this->auth           = $auth;
        $this->messages       = $messages;
        $this->jwt            = $jwt;
        $this->renderer       = $renderer;
    }

    /**
     * Authentication middleware invokable class
     *
     * @param  Request  $request
     * @param  Response $response
     * @param  callable $next
     *
     * @return Response
     */
    public function __invoke(Request $request, Response $response, $next): Response
    {
        $this->setState($request);

        if ($this->state === 'authenticated' && $request->hasHeader('Authorization')) {
            list($jwt) = sscanf($request->getHeader('Authorization')[0], 'Bearer %s');
            $token = $this->auth->decodeJWT($this->jwt, $jwt);
            if ($jwt && $token) {
                $userId   = $token->data->id;
                $user = $this->userRepository->getUser($userId);
                if (!$user || $token->iss != $_SERVER['APP_PROTOCOL'] . $_SERVER['APP_DOMAIN']) {
                    $this->messages->setErrors('AUTH-0002');
                }
            } else {
                $this->messages->setErrors('AUTH-0004');
            }
        } elseif ($this->state === 'authenticated' && !$request->hasHeader('Authorization')) {
            $this->messages->setErrors('AUTH-0002');
        }

        if ($this->state === 'anonymous' && $request->hasHeader('Authorization')) {
            $this->messages->setErrors('AUTH-0003');
        }

        if ($this->messages->hasErrors()) {
            $errors = $this->messages->getErrors();
            $response = $this->renderer->render($request, $response, $errors);
            return $response->withStatus($errors['errors'][0]['status']);
        } else {
            return $next($request, $response);
        }
    }

    /**
     * Set the auth state based on route argument
     *
     * @param Request $request
     */
    public function setState(Request $request)
    {
        $route = $request->getAttribute('route');
        $state = $route->getArgument('state');

        if ($state && $this->validateState($state)) {
            $this->state = $state;
        }
    }

    /**
     * Validate the state
     *
     * @param  string $state
     * @return bool
     */
    public function validateState($state): bool
    {
        $validStates = ['authenticated', 'anonymous'];
        if (in_array($state, $validStates)) {
            return true;
        } else {
            $this->messages->setErrors('AUTH-0001');
            return false;
        }
    }
}

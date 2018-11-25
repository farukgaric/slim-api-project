<?php

namespace App\Actions\Auth;

use App\Domain\Users\UserInterface;
use App\Services\Auth;
use App\Services\Messages;
use Firebase\JWT\JWT;
use RKA\ContentTypeRenderer\Renderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class Signin
{
    /**
     * @param UserInterface   $userRepository
     * @param Auth            $auth
     * @param Messages        $messages
     * @param JWT             $jwt
     * @param Renderer        $renderer
     */
    public function __construct(UserInterface $userRepository, Auth $auth, Messages $messages, JWT $jwt, Renderer $renderer)
    {
        $this->userRepository  = $userRepository;
        $this->auth            = $auth;
        $this->messages        = $messages;
        $this->jwt             = $jwt;
        $this->renderer        = $renderer;
    }

    /**
     * Signin using json web tokens
     *
     * @param  Request  $request
     * @param  Response $response
     *
     * @return array
     */
    public function __invoke(Request $request, Response $response)
    {
        $input     = $request->getParsedBody();

        if ($user = $this->auth->login($input)) {
            $message = $this->messages->getDetails('COUNTRY-0008');
            $jwt = $this->auth->generateJWT($this->jwt, $user);
            $data = array_merge($message, $jwt);
            
            $response = $this->renderer->render($request, $response, $data);
            return $response->withStatus(200);
        }
        $this->messages->setErrors('COUNTRY-0009');
        return $this->messages->throwErrors($request, $response, $this->renderer);
    }
}

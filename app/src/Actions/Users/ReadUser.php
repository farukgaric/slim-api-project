<?php

namespace App\Actions\Users;

use App\Domain\Users\UserInterface;
use App\Domain\Users\UserTransformer;
use App\Services\Auth;
use App\Services\Messages;
use App\Services\Transformer;
use RKA\ContentTypeRenderer\Renderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\JWT;

final class ReadUser
{
    /**
     * @param UserRepository  $userRepository
     * @param UserTransformer $userTransformer
     * @param Messages        $messages
     * @param Transformer     $transformer
     * @param Renderer        $renderer
     */
    public function __construct(UserInterface $userRepository, UserTransformer $userTransformer, Auth $auth, JWT $jwt, Messages $messages, Transformer $transformer, Renderer $renderer)
    {
        $this->userRepository  = $userRepository;
        $this->userTransformer = $userTransformer;
        $this->auth            = $auth;
        $this->jwt             = $jwt;
        $this->messages        = $messages;
        $this->transformer     = $transformer;
        $this->renderer        = $renderer;
    }

    /**
     * Show a user
     *
     * @param  Request  $request
     * @param  Response $response
     * @param  array    $args
     *
     * @return array
     */
    public function __invoke(Request $request, Response $response)
    {
        list($jwt) = sscanf($request->getHeader('Authorization')[0], 'Bearer %s');
        $payload = $this->auth->decodeJWT($this->jwt, $jwt);
        $user = $this->userRepository->getUser($payload->data->id);

        if ($user) {
            $data = $this->transformer->respondWithItem($user, $this->userTransformer);
            $response = $this->renderer->render($request, $response, $data);
            return $response->withStatus(200);
        }
        $this->messages->setErrors('USER-0003');
        $this->messages->throwErrors($request, $response);
    }
}

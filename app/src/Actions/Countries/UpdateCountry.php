<?php

namespace App\Actions\Countries;

use App\Domain\Countries\CountryInterface;
use App\Domain\Countries\CountryTransformer;
use App\Services\Auth;
use App\Services\Messages;
use App\Services\Transformer;
use RKA\ContentTypeRenderer\Renderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class UpdateCountry
{
    /**
     * @param CountryInterface  $countryRepository
     * @param CountryTransformer $countryTransformer
     * @param Messages        $messages
     * @param Transformer     $transformer
     * @param Renderer        $renderer
     */
    public function __construct(CountryInterface $countryRepository, CountryTransformer $countryTransformer, Messages $messages, Transformer $transformer, Renderer $renderer)
    {
        $this->countryRepository  = $countryRepository;
        $this->countryTransformer = $countryTransformer;
        $this->messages           = $messages;
        $this->transformer        = $transformer;
        $this->renderer           = $renderer;
    }

    /**
     * List countries
     *
     * @param  Request  $request
     * @param  Response $response
     * @param  array    $args
     *
     * @return array
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $userId = intval($args['id']);
        if ($this->countryRepository->updateCountry($userId, $data)) {
            $country      = $this->countryRepository->getCountry($userId);
            $message      = $this->messages->getDetails('COUNTRY-0004');
            $responseData = array_merge($message, $this->transformer->respondWithItem($country, $this->countryTransformer));
            $response     = $this->renderer->render($request, $response, $responseData);
            return $response->withStatus(200);
        }
        $this->messages->setErrors('COUNTRY-0005');
        $this->messages->throwErrors($request, $response, $this->renderer);
    }
}

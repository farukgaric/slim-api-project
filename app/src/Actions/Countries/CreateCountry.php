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

final class CreateCountry
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
     *
     * @return array
     */
    public function __invoke(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        if ($country      = $this->countryRepository->addCountry($data)) {
            $message      = $this->messages->getDetails('COUNTRY-0001');
            $responseData = array_merge($message, $this->transformer->respondWithItem($country, $this->countryTransformer));
            $response     = $this->renderer->render($request, $response, $responseData);
            return $response->withStatus(201);
        }
        $this->messages->setErrors('COUNTRY-0002');
        $this->messages->throwErrors($request, $response, $this->renderer);
    }
}

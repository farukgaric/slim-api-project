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

final class ReadCountries
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
        $this->messages        = $messages;
        $this->transformer     = $transformer;
        $this->renderer        = $renderer;
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
    public function __invoke(Request $request, Response $response)
    {
        $page  = $request->getParam('page');
        $perPage  = $request->getParam('perPage');
        $page  = (isset($page) && intval($page) > 0) ? intval($page) - 1 : 0;
        $perPage  = (isset($perPage) && intval($perPage) > 0) ? intval($perPage) : 15;
        $countries = $this->countryRepository->getCountries($page, $perPage);

        if ($countries) {
            $data = $this->transformer->respondWithCollection($countries, $this->countryTransformer);
            $response = $this->renderer->render($request, $response, $data);
            return $response->withStatus(200);
        }
        $this->messages->setErrors('COUNTRY-0010');
        $this->messages->throwErrors($request, $response, $this->renderer);
    }
}

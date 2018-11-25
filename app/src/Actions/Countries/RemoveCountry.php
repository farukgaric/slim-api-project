<?php

namespace App\Actions\Countries;

use App\Domain\Countries\CountryInterface;
use App\Services\Auth;
use App\Services\Messages;
use RKA\ContentTypeRenderer\Renderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class RemoveCountry
{
    /**
     * @param CountryInterface  $countryRepository
     * @param Messages        $messages
     * @param Renderer        $renderer
     */
    public function __construct(CountryInterface $countryRepository, Messages $messages, Renderer $renderer)
    {
        $this->countryRepository  = $countryRepository;
        $this->messages           = $messages;
        $this->renderer           = $renderer;
    }

    /**
     * Show a country
     *
     * @param  Request  $request
     * @param  Response $response
     * @param  array    $args
     *
     * @return array
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        $countryId   = (int) $args['id'];

        if ($this->countryRepository->removeCountry($countryId)) {
            $message  = $this->messages->getDetails('COUNTRY-0006');
            $response = $this->renderer->render($request, $response, $message);
            return $response->withStatus(200);
        }
        $this->messages->setErrors('COUNTRY-0007');
        $this->messages->throwErrors($request, $response, $this->renderer);
    }
}

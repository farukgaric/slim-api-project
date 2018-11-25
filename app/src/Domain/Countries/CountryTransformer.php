<?php declare(strict_types=1);

namespace App\Domain\Countries;

use League\Fractal\TransformerAbstract;
use App\Domain\Countries\Country;

class CountryTransformer extends TransformerAbstract
{
    /**
     * List of fields to filter
     * On production we don\'t want to reveal things like tokens
     * that would allow somebody to reset a users\' password.
     * However we need them in development for testing.
     *
     * @var array $fields
     */
    public function __construct()
    {
        $this->filterFields = [];
    }

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(Country $country)
    {
        return $this->transformWithFieldFilter(
            [
                'id' => (int) $country->id,
                'attributes' => [
                    'name'           => (string) $country->name,
                    'capital_city'   => (string) $country->capital_city,
                    'population'     => (string) $country->population,
                    'dialing_code'   => (string) $country->dialing_code,
                    'continent'      => (string) $country->continent,
                    'created'        => date('D, d M Y H:i:s', time($country->created)),
                ],
                'links' => [
                    [
                        'self' => '/countries/' . (int) $country->id,
                        'related' => []
                    ],
                ]
            ]
        );
    }

    /**
     * Filter fields
     *
     * @return League\Fractal\ItemResource
     */
    protected function transformWithFieldFilter($data)
    {
        if (is_null($this->filterFields) || getenv('APP_ENV') === 'dev') {
            return $data;
        }

        return array_diff_key($data, array_flip((array) $this->filterFields));
    }
}

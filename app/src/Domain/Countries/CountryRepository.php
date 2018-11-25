<?php declare(strict_types=1);

namespace App\Domain\Countries;

use App\Domain\Countries\CountryInterface;
use App\Domain\Countries\Country;

class CountryRepository implements CountryInterface
{
    /**
     * @var Country
     */
    protected $model;
    /**
     * CountryRepository constructor.
     *
     * @param Country $model
     */
    public function __construct(Country $model)
    {
        $this->model = $model;
    }

    /**
     * Retrieve data of a all countries
     *
     * @param int $id
     * @param array $data
     *
     * @return array
     */
    public function getCountries(int $page, int $perPage): array
    {
        return $this->model->offset($page * $perPage)->limit($perPage)->get()->all();
    }

    /**
     * Remove data of a specific Country
     *
     * @param int $countryId
     *
     * @return bool
     */
    public function removeCountry(int $countryId): bool
    {
        return (bool)$this->model->where('id', $countryId)->delete();
    }

    /**
     * Update data of a specific Country
     *
     * @param int $countryId
     * @param array $data
     *
     * @return bool
     */
    public function updateCountry(int $countryId, array $data): bool
    {
        return (bool)$this->model->where('id', $countryId)->update($data);
    }

    /**
     * Get a specific Country
     *
     * @param int $countryId
     *
     * @return Country|null
     */
    public function getCountry(int $countryId): Country
    {
        $country = $this->model->find($countryId);
        return $this->returnCountryInstanceOrNull($country);
    }

    /**
     * Add a new Country
     *
     * @param array $data
     *
     * @return Country|null
     */
    public function addCountry(array $data): Country
    {
        $country = $this->model->create($data);
        return $this->returnCountryInstanceOrNull($country);
    }

    /**
     * Return Country instance or null
     *
     * @param $country
     *
     * @return Country|null
     */
    private function returnCountryInstanceOrNull($country): Country
    {
        if ($country instanceof Country) {
            return $country;
        }
        return null;
    }
}

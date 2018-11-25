<?php declare(strict_types=1);

namespace App\Domain\Countries;

use App\Domain\Countries\Country;

interface CountryInterface
{
    public function getCountry(int $countryId):? Country;
    public function getCountries(int $page, int $perPage):? array;
    public function removeCountry(int $countryId):? bool;
    public function updateCountry(int $countryId, array $data):? bool;
    public function addCountry(array $data):? Country;
}

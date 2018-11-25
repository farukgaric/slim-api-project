<?php declare(strict_types=1);

namespace App\Domain\Countries;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'countries';
    /**
     * @var array
     */
    protected $fillable = ['name', 'capital_city', 'population', 'dialing_code', 'contintent'];
    /**
     * @var array
     */
    protected $hidden = [];
    /**
     * @var array
     */
    protected $dates = ['created'];
}

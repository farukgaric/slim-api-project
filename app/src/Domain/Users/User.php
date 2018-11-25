<?php declare(strict_types=1);

namespace App\Domain\Users;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * @var string
     */
    protected $table = 'users';
    /**
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'token', 'status'];
    /**
     * @var array
     */
    protected $hidden = [];
    /**
     * @var array
     */
    protected $dates = ['created'];
}

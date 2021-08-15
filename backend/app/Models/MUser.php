<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Support\Str;

class MUser extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasApiTokens;
    
    /**
     * The attributes that are mass assignable.
     * https://medium.com/the-andela-way/setting-up-oauth-in-lumen-using-laravel-passport-2de9d007e0b0
     *
     * @var array
     */

    protected $table = 'm_user';
    protected $hidden = ['password'];

    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'alias_code',
        'alias_name',
        'enabled',
        'uid',
    ];

    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps =true;

    public function findForPassport($username) {
        return $this->orWhere('username', $username)
            ->orWhere('email', $username)
            ->first();
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot() {
        parent::boot();

        // Listen creating event
        self::creating(function ($model) {
            $model->uid = (string) Str::uuid();
        });
    }
}

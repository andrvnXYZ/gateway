<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable as AuthorizableTrait;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject; // 1. I-IMPORT NI NGA INTERFACE

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject // 2. I-ADD ANG JWTSubject DIRI
{
    use Authenticatable, AuthorizableTrait;

    protected $table = 'users'; // Siguroha nga 'users' ang table name sa site1

    protected $fillable = [
        'username', 'password',
    ];

    protected $hidden = [
        'password',
    ];

    // 3. I-ADD KINING DUHA KA REQUIRED METHODS SA UBOS:

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
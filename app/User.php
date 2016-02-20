<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function posts()
    {
        return $this->hasMany('App\Posts');
    }

    public function comments()
    {
        return $this->hasMany('App\Comments');
    }

    public function responses()
    {
        return $this->hasMany('App\Responses');
    }

    public function is_admin()
    {
        return $this->role == 'admin';
    }
    public function is_moderator()
    {
        return $this->role == 'moderator';
    }
}


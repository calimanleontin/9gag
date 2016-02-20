<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    protected $guarded = [];

    public function post()
    {
        return $this->belongsTo('App\Posts');
    }

    public function responses()
    {
        return $this->hasMany('App\Responses');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
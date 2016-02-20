<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function comments()
    {
        return $this->hasMany('App\Comments');
    }

    public function noComments()
    {
        return count($this->comments()->get());
    }

    public function category()
    {
        return $this->belongsTo('App\Categories');
    }
}
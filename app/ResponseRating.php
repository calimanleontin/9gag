<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class ResponseRating extends Model
{
    protected $guarded = [];
    public $table = 'response_rating';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function response()
    {
        return $this->belongsTo('App\Response','response_id');
    }
}
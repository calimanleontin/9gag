<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Responses extends Model
{
    protected $guarded = [];
    protected $table = 'comments_responses';

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function comment()
    {
        return $this->belongsTo('App\Comments');
    }
}
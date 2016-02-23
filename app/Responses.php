<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Responses extends Model
{
    protected $guarded = [];
    protected $table = 'comments_responses';

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function comment()
    {
        return $this->belongsTo('App\Comments', 'comment_id');
    }

    public function getRating($user_id, $response_id)
    {
        $rating = ResponseRating::where('user_id', $user_id)->where('response_id', $response_id)->first();
        return $rating;
    }
}
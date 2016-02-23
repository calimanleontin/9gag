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
        return $this->hasMany('App\Responses','comment_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getRating($comment_id, $user_id)
    {
        $rating = CommentRating::where('comment_id',$comment_id)->where('user_id', $user_id)->first();
        return $rating;
    }
}
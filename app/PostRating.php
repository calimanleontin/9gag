<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class PostRating extends Model
{
    public $table = 'post_rating';
    protected $guarded = [];
    public $timestamps = false;

}
<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    public $table = 'rating';
    protected $guarded = [];
    public $timestamps = false;

}
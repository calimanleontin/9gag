<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model{
    protected $guarded =[];

    public function posts()
    {
        return $this->hasMany('App\Posts','category_id');
    }
}

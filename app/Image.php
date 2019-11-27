<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['image'];

    public function imagable(){
        return $this->morphTo();
    }
}

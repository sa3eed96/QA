<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Question;

class Notification extends Model
{
    protected $fillable = ['body', 'question_slug', 'read']; 

    public function user(){
        return $this->belongsTo(User::class);
    }
}

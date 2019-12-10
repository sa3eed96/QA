<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['body', 'user_id']; 

    protected $appends = ['created_date', 'body_html', 'is_best', 'excerpt', 'is_to_long'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function question(){
        return $this->belongsTo(Question::class);
    }

    public function images(){
        return $this->morphMany('App\Image', 'imagable');
    }

    public function getBodyHtmlAttribute(){
        return htmlspecialchars_decode($this->body);
    }

    public static function boot(){
        parent::boot();

        static::created(function($answer){
            $answer->question->increment('answers_count');
        });

        static::deleted(function($answer){
            $answer->question->decrement('answers_count');
        });

    }

    public function getCreatedDateAttribute(){
        return $this->created_at->diffForHumans();
    }

    public function getStatusAttribute(){
        return $this->id === $this->question->best_answer_id ? 'vote-accepted': '';
    }

    public function getIsBestAttribute(){
        return $this->id === $this->question->best_answer_id ? true: false;
    }

    public function getIsToLongAttribute(){
        return strlen($this->body) > 250;
    }

    public function votes(){
        return $this->morphToMany(User::class,'votable');
    }

    public function getExcerptAttribute(){
        return str_limit(htmlspecialchars_decode($this->body), 250);
    }
}

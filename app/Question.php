<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Question extends Model
{
    protected $fillable = ['title', 'body'];

    protected $appends = ['created_date', 'is_favourited', 'favourites_count','body_html', 'excerpt'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function tags(){
        return $this->hasMany(Tag::class);
    }

    public function setTitleAttribute($value){
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = str_slug($value);
    }

    public function getUrlAttribute(){
        return route("question.show", $this->slug);
    }

    public function getCreatedDateAttribute(){
        return $this->created_at->diffForHumans();
    }

    public function getStatusAttribute(){
        if($this->answers_count > 0){
            if($this->best_answer_id){
                return "answered-accepted";
            }
            return "answered";
        }
        return "unanswered";
    }

    public function getBodyHtmlAttribute(){
        return clean($this->bodyHtml());
    }

    public function answers(){
        return $this->hasMany(Answer::class)->orderBy('votes_count','DESC');;
    }

    public function acceptBestAnswer(Answer $answer)
    {
        $this->best_answer_id = $answer->id;
        $this->save();
    }

    public function favourites(){
        return $this->belongsToMany(User::class,'favourites')->withTimestamps();
    }

    public function getIsFavouritedAttribute(){
        return $this->favourites()->where('user_id', Auth::id())->count() > 0;
    }

    public function getFavouritesCountAttribute(){
        return $this->favourites->count();
    }

    public function votes(){
        return $this->morphToMany(User::class,'votable');
    }

    public function images(){
        return $this->morphMany('App\Image', 'imagable');
    }

    public function getExcerptAttribute(){
        return str_limit(strip_tags($this->bodyhtml()), 250);
    }

    private function bodyHtml(){
        return \Parsedown::instance()->text($this->body);
    }
}

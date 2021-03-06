<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','country', 'age', 'reputation'
    ];

    protected $appends = ['url', 'avatar'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function questions(){
        return $this->hasMany(Question::class);
    }

    public function getUrlAttribute(){
        return route("user.show", $this->id);
    }

    public function answers(){
        return $this->hasMany(Answer::class);
    }

    public function getAvatarAttribute(){
        $email = $this->email;
        $size = 22;
        return "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "&s=" . $size;
    }

    public function favourites(){
        return $this->belongsToMany(Question::class,'favourites')->withTimestamps();
    }

    public function voteQuestions(){
        return $this->morphedByMany(Question::class, 'votable');
    }

    public function voteAnswers(){
        return $this->morphedByMany(Answer::class, 'votable');
    }

    public function voteQuestion(Question $question, $vote){
        $voteQuestions = $this->voteQuestions();
        return $this->_vote($voteQuestions, $question, $vote);
    }

    
    public function voteAnswer(Answer $answer, $vote){
        $voteAnswers = $this->voteAnswers();
        return $this->_vote($voteAnswers, $answer, $vote);
    }


    public function updateVoteReputation($model, $vote){
        $user = $model->user()->get()[0];
        $currentUser = Auth::user();
        if(Auth::user() && $user['id'] !== $currentUser->id){
            $reputation = $user['reputation'];
            $currentUserReputation = $currentUser->reputation; 
            if($vote === 1)
                $reputation += 10;
            else if($reputation < 4)
                $reputation = 1;
            else
                $reputation -= 2;
            
            if($vote === -1 && $currentUserReputation > 3)
                $currentUserReputation -= 2;
            else if($vote === -1)
                $currentUserReputation = 1;
            $model = DB::transaction(function() use($reputation, $currentUserReputation){
                $user->update(['reputation' => $reputation]);
                $currentUser->update(['reputation' => $currentUserReputation]);
                $model->load('votes');
                $upVotes = (int) $model->votes()->wherePivot('vote', 1)->sum('vote');
                $downVotes = (int) $model->votes()->wherePivot('vote', -1)->sum('vote');
                $model->votes_count = $upVotes + $downVotes;
                $model->save();
                return $model;
            });
            return $model;
        }
    }

    private function _vote($relationship, $model, $vote){
        if($relationship->where('votable_id',$model->id)->exists()){
            $relationship->updateExistingPivot($model, ['vote' => $vote]);
        }
        else{
            $relationship->attach($model, [ 'vote' => $vote ]);
        }
        $model = $this->updateVoteReputation($model, $vote);

        return $model['votes_count'];
    }
}

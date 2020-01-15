<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function  questions()
    {
        return  $this->hasMany(Question::class);
    }

    public function getUrlAttribute()
    {
        # code...
       //return route("questions.show",$this->id);
       return "#";
    }
    public function answers()
    {
        # code...
        return $this->hasMany(Answer::class);
    }
    public  function getAvatarAttribute()
    {
        # code...
        $email = $this->email;
        $size = 20; 
        return "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ). "&s=" . $size;
    }


    public function favorites()
    {
        return $this->belongsToMany(Question::class, 'favorites')->withTimestamps(); //, 'author_id', 'question_id');
    }


    public function voteQuestions()
    {
        # code...
        return $this->morphedByMany(Question::class,'votable');
    }
    public function voteAnswers()
    {
        # code...
        return $this->morphedByMany(Answer::class,'votable');
    }

    public function voteQuestion(Question $question,$vote)
    {
        # code...
       $voteQuestions= $this->voteQuestions();

       if($voteQuestions->where('votable_id',$question->id)->exists()){
           $voteQuestions->updateExistingPivot($question,['vote'=>$vote]);
       }
       else{
           $voteQuestions->attach($question,['vote'=>$vote]);
       }
       $question->load('votes');
       $downVotes= (int)$question->votes()->wherePivot('vote',-1)->sum('vote');
       $upVotes= (int)$question->votes()->wherePivot('vote',1)->sum('vote');

       $question->votes_count = $upVotes +$downVotes;
       $question->save();
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    //
    protected $fillable=['body','user_id'];
    public function question()
    {
        # code...
        return $this->belongsTo(Question::class);
    }

    public function user()
    {
        # code...

        return $this->belongsTo(User::class);
    }
    public function getBodyHtmlAttribute()
    {
        # code...
        return  \Parsedown::instance()->text($this->body);
    }
    public static function boot()
    {
        # code...
        parent::boot();

        static::created(function($answer){
            $answer->question->increment('answers_count');
           
        });


        static::deleted(function($answer){
            $question = $answer->question;
            $question->decrement('answers_count');
            if($question->best_answer_id===$answer->id){
                $question->best_answer_id = NULL;
                $question->save();
            }
            
        });
       
    }
    public function getCreatedDateAttribute()
    {
        # code...
        return $this->created_at->diffForHumans();
    }
    public function getStatusAttribute()
    {
        # code...
        return $this->id === $this->question->best_answer_id ? 'vote-accepted' :'';    }
}

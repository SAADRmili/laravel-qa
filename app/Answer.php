<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    //

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
            $answer->question->save();
        });

       
    }
    public function getCreatedDateAttribute()
    {
        # code...
        return $this->created_at->diffForHumans();
    }
}

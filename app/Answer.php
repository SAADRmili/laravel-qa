<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    //
    protected $fillable=['body','user_id'];

    protected $appends=['created_date','body_html','is_best'];
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
           
            $answer->question->decrement('answers_count');
          
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
        return $this->isBest()? 'vote-accepted' :'';   
     }

    public function getIsBestAttribute()
    {
        # code...
        return $this->IsBest();
    }

    public function IsBest()
    {
        # code...
        return  $this->id === $this->question->best_answer_id ;
    }
    public function votes()
    {
        # code...
        return $this->morphToMany(User::class,'votable');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    //
    protected $fillable =['title','body'];


    public function  user(){
        return $this->belongsTo(User::class);
    }

    public function setTitleAttribute($value)
    {
        # code...
        $this->attributes['title']=$value;
        $this->attributes['slug']=str_slug($value);
    }

    public function getUrlAttribute()
    {
        # code...
       return route("questions.show",$this->slug);
    }


    public function getCreatedDateAttribute()
    {
        # code...
        return $this->created_at->diffForHumans();
    }
    public function getStatusAttribute()
    {
        # code...
        if($this->answers_count>0){
            if($this->best_answer_id){
                return "answered-accepted";
            }
            return "answered";
        }
        return "unanswered";
    }

    public function getBodyHtmlAttribute()
    {
        # code...
        return  \Parsedown::instance()->text($this->body);
    }

    public function answers()
    {
        # code...
        return $this->hasMany(Answer::class);
    }

    public function acceptBestAnswer(Answer $answer)
    {
        # code...
        $this->best_answer_id = $answer->id;
        $this->save();
    }
}

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

        return clean($this->bodyHtml());
       
    }

    public function answers()
    {
        # code...
        return $this->hasMany(Answer::class)->orderBy('votes_count','DESC');
    }

    public function acceptBestAnswer(Answer $answer)
    {
        # code...
        $this->best_answer_id = $answer->id;
        $this->save();
    }
    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps(); //, 'question_id', 'user_id');
    }
    public function isFavorited()
    {
        return $this->favorites()->where('user_id', auth()->id())->count() > 0;
    }
    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }
    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }


    public function votes()
    {
        # code...
        return $this->morphToMany(User::class,'votable');
    }


    public  function getExcerptAttribute()
    {
        # code...
       return str_limit(strip_tags($this->bodyHtml()),300);
    }
    public function bodyHtml()
    {
        # code...*
         return  \Parsedown::instance()->text($this->body);
    }

}

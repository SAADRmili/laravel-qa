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
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
class VoteQuestionController extends Controller
{
    //
        public function __construct()
        {
            $this->middleware('auth');
        }

        public function __invoke(Question $question)
        {
            # code...
            $vote = (int) request()->vote;
            
           $votescount= auth()->user()->voteQuestion($question,$vote);
            if(request()->expectsJson()){
                return response()->json([
                    'message' => "Thanks for the feedback" ,
                    'votesCount'=>$votescount
                ]);
            }
            return back();
        }
}

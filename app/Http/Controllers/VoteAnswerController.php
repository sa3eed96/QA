<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Answer;

class VoteAnswerController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function __invoke(Answer $answer){
        $vote = (int) request()->vote;
        $votesCount = auth()->user()->voteAnswer($answer, $vote);
        return response()->json([
            'message' => 'vote submitted',
            'votesCount' => $votesCount
        ]);
    }
}

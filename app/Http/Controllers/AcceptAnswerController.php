<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Answer;
use App\Events\AcceptEvent;

class AcceptAnswerController extends Controller
{
    public function __invoke(Answer $answer){
        $this->authorize('accept', $answer);
        $answer->question->acceptBestAnswer($answer);
        $answer->user()->get()[0]->notify(new AcceptNotification($answer->question()->get()[0]));
        return response()->json([
            'message'=> 'Answer Accepted'
        ]);
    }
}

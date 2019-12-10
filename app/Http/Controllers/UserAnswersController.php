<?php

namespace App\Http\Controllers;

use App\Answer;
use App\User;
use Illuminate\Http\Request;

class UserAnswersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $answers = $user->answers()
            ->join('questions','questions.id','=','answers.question_id')
            ->select('answers.*','questions.title as question_title','questions.slug as question_slug')
            ->paginate(1);
        return response()->json([
            'answers' => $answers
        ]);
    }
}

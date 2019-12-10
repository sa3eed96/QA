<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserQuestionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $questions = $user->questions()->paginate(1);
        return response()->json([
            'questions' => $questions
        ]);
    }
}

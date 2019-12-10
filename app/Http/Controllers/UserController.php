<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function __construct(){
        $this->middleware('auth')->except('index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $questions = $user->questions()->get();
        $answers = $user->answers()
            ->join('questions','questions.id','=','answers.question_id')
            ->select('answers.*','questions.title as question_title','questions.slug as question_slug')
            ->get();
        return view('profile',[
            'user' => $user,
            'questions' => $questions,
            'answers' => $answers
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $this->authorize('update', $user);
        $user->update($request->only('name','age','country'));
        return response()->json([
            'name' => $user->name,
            'age' => $user->age,
            'country' => $user->country
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();
        return response()->json([
            'message' => 'Account Deactivated'
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Image;
use App\Events\ReplyEvent;
use App\Http\Requests\AnswerRequest;
use App\Notifications\ReplyNotification;

class AnswerController extends Controller
{

    public function __construct(){
        $this->middleware('auth')->except('index');
    }

    public function index(Question $question){
         return $question->answers()->with('user')->simplePaginate(5);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Question $question, AnswerRequest $request)
    {
        $answer = DB::transaction(function() use($request, $question){
            $answer = $question->answers()->create(['body' => htmlspecialchars($request->input('body')), 'user_id' => Auth::id()]);
            if($request->file('images')){
                $images = [];
                for ($i=0; $i < count($request->file('images')); $i++){
                    array_push($images, new Image(['image' => $request->file('images')[$i]->openFile()->fread($request->file('images')[$i]->getSize())]));
                }
                $answer->images()->saveMany($images);
            }
            $question->user()->get()[0]->notify(new ReplyNotification($question));
            return $answer;
        });
        return response()->json([
            'message' => 'Answer Created',
            'answer' => $answer->load('user')
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question,Answer $answer)
    {
        $this->authorize('update', $answer);

        return view('answers.edit',compact('question','answer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function update(AnswerRequest $request, Question $question, Answer $answer)
    {
        $this->authorize('update', $answer);
        $answer = DB::transaction(function() use($request, $answer){
            $answer->update(['body' => $request->body]);
            if($request->removedImages)
                $answer->images()->wherein('id',$request->removedImages)->delete();
            return $answer;
        });
        return response()->json([
            'message' => 'Your answer is updated',
            'body_html' => $answer->body_html
        ]);
        //return redirect()->route('question.show',$question->slug)->with('success','Answer Edited!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question, Answer $answer)
    {
        $this->authorize('delete', $answer);
        DB::transaction(function() use($answer){
            $answer->images()->delete();
            $answer->votes()->delete();
            $answer->delete();
        });
        return response()->json([
            'message' => 'Your Answer is Deleted'
        ]);
    }
}

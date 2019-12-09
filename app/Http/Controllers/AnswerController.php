<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Image;
use App\Events\ReplyEvent;

class AnswerController extends Controller
{

    public function __construct(){
        $this->middleware('auth')->except('index');
    }

    public function index(Question $question){
         return $question->answers()->with('user')->simplePaginate(3);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Question $question, Request $request)
    {
        $answer = $question->answers()->create(['body' => $request->input('body'), 'user_id' => Auth::id()]);
        if($request->hasFile('0')){
            $i=0;
            $images = [];
            while($request->hasFile(''.$i)){
                $file = $request->file(''.$i);
                array_push($images, new Image(['image'=> $file->openFile()->fread($file->getSize())]));
                ++$i;
            }
            $answer->images()->saveMany($images);
        }
        broadcast(new ReplyEvent($question));
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
    public function update(Request $request, Question $question, Answer $answer)
    {
        $this->authorize('update', $answer);
        $answer->update($request->validate([
            'body' => 'required'
        ]));
        if($request->removedImages)
            $answer->images()->wherein('id',$request->removedImages)->delete();
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
        $answer->delete();
        return response()->json([
            'message' => 'Your Answer is Deleted'
        ]);
    }
}

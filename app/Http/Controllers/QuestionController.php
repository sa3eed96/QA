<?php

namespace App\Http\Controllers;

use App\Question;
use App\Tag;
use Illuminate\Http\Request;
use App\Http\Requests\AskQuestionRequest;
use function Rap2hpoutre\RemoveStopWords\remove_stop_words;

class QuestionController extends Controller
{

    public function __construct(){
        $this->middleware('auth', ['except' => ['index','show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $questions = Question::with('user');
        if($request->query('tags')){
            $words = explode(',', $request->query('tags'));
            $tagsQuestionId = Tag::select('question_id');
            foreach($words as $word){ 
                $tagsQuestionId = $tagsQuestionId->orwhere('tag',$word);
            }
            $questions = $questions->wherein('id',$tagsQuestionId);
        }
        if($request->query('sentence')){
            $words = explode(' ',preg_replace('/\s+/',' ',remove_stop_words($request->query('sentence'))));
            foreach($words as $word){
                $questions = $questions->orwhere('body','like','%'.$word.'%');
            }
        }
        $questions = $questions->latest()->paginate(5);
        return view('questions.index',compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $question = new Question();
        return view('questions.create',compact('question'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AskQuestionRequest $request)
    {
        $request->user()->questions()->create($request->only('title', 'body'));
        
        return redirect()->route('question.index')->with('success', "Your Question is Asked");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        $question->increment('views');
        return view('questions.show',compact('question'));   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        return view('questions.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(AskQuestionRequest $request, Question $question)
    {
        $question->update($request->only('title', 'body'));
        return response()->json([
            'message' => 'Question Edited',
            'body_html' => $question->body_html
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $question->delete();
        return response()->json([
            'message' => 'Question Deleted'
        ]);
    }
}

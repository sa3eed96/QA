<?php

namespace App\Http\Controllers;

use App\Question;
use App\Tag;
use App\Image;
use Illuminate\Http\Request;
use App\Http\Requests\AskQuestionRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        DB::transaction(function() use($request){
            $question = $request->user()->questions()->create([
                'title'=> $request->input('title'),
                'body' => htmlspecialchars($request->input('body'))
            ]);
            if($request->input('tags')){
                $tags = [];
                foreach (explode(",", $request->input('tags')) as $tag)
                array_push($tags, new Tag(['tag' => $tag]));
                $question->tags()->saveMany($tags);
            }
            if($request->file('images')){
                $images = [];
                for ($i=0; $i < count($request->file('images')); $i++){
                    array_push($images, new Image(['image' => $request->file('images')[$i]->openFile()->fread($request->file('images')[$i]->getSize())]));
                }
                $question->images()->saveMany($images);
            }
            return response()->json([
                'slug' =>  $question->slug
            ]);
        });
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
        $this->authorize('update', $question);
        DB::transaction(function() use($request, $question){
            $question->update($request->only('title', 'body'));
            if($request->removedTags)
                $question->tags()->wherein('id',$request->removedTags)->delete();
            if($request->removedImages)
                $question->images()->wherein('id',$request->removedImages)->delete();
            if($request->newTags){
                $tags = [];
                foreach ($request->newTags as $tag)
                array_push($tags, new Tag(['tag' => $tag]));
                $question->tags()->saveMany($tags);
            }
        });
        return response()->json([
            'message' => 'Question Edited',
            'body_html' => $question->body_html,
            'tags' => $question->tags()->get()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,Question $question)
    {
        $this->authorize('delete', $question);
        DB::transaction(function() use($question){
            $question->images()->delete();
            $question->votes()->delete();
            $question->delete();
        });
        if($request->expectsJson())
            return response()->json([
                'message' => 'Question Deleted'
            ]);
        return redirect('question'); 
    }
}

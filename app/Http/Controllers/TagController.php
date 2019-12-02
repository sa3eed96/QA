<?php

namespace App\Http\Controllers;

use App\Question;
use App\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{

    public function __construct(){
        $this->middleware('auth', ['except' => ['index']]);
    }

    public function index()
    {
    }

    public function destroy(Question $question)
    {
        $tags = $request->tags;
        $question = $question->tags(); 
        foreach ($tags as $tag) {
            $question = $question->wherein('id',$tag->id);
        }
        $question->delete();
        return response()->json([
            'message' => 'tags removed'
        ]);
    }
}

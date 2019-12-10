<?php

namespace App\Http\Controllers;

use App\Question;
use App\Tag;
use Illuminate\Http\Request;
use DB;

class TagController extends Controller
{

    public function index()
    {
        return view('tags',[
            'tags'=> Tag::select(DB::raw('tag, count(*) as count'))->groupBy('tag')->orderBy('count','Desc')->simplePaginate(50)
        ]);
    }

}

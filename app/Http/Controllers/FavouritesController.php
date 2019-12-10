<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Question;
use App\User;

class FavouritesController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(User $user){
        if($user->id === Auth::id())
            return view('favourites',["favourites" => $user->favourites()->paginate(10)]);
        else
            return abort(404);
    }

    public function store(Question $question){
        $question->favourites()->attach(auth()->id());
        return response()->json(null, 204);
    }
    
    public function destroy(Question $question){
        $question->favourites()->detach(auth()->id());
        return response()->json(null, 204);
    } 
}

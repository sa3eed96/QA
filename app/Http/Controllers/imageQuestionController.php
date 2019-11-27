<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\Request;

class imageQuestionController extends Controller
{
    public function __invoke(Question $question){
        $binaryImages = $question->images()->select('image')->get();
        $base64Images = [];
        foreach ($binaryImages as $image) {
            array_push($base64Images,base64_encode($image->image));  
        }
        //return response('<img src="data:image/jpeg;base64,'.base64_encode($k[0]->image).'">');
        return response()->json([
            'images' => $base64Images
        ]);
    }
}

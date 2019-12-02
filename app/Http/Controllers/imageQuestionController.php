<?php

namespace App\Http\Controllers;

use App\Image;
use App\Question;
use Illuminate\Http\Request;

class ImageQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function index(Question $question)
    {
        $binaryImages = $question->images()->select('id','image')->get();
        $base64Images = [];
        foreach ($binaryImages as $image) {
            array_push($base64Images,['id'=> $image->id,'image' => base64_encode($image->image)]);  
        }
        //return response('<img src="data:image/jpeg;base64,'.base64_encode($k[0]->image).'">');
        return response()->json([
            'images' => $base64Images
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $question
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question, Image $image)
    {
        $question->images()->where('id',$image->id)->delete();
        return response()->json([
            'message' => 'Image Deleted'
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Image;
use Illuminate\Http\Request;

class ImageAnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function index(Answer $answer)
    {
        $binaryImages = $answer->images()->select('id','image')->get();
        $base64Images = [];
        foreach ($binaryImages as $image) {
            array_push($base64Images,['id'=> $image->id,'image' => base64_encode($image->image)]);  
        }
        return response()->json([
            'images' => $base64Images
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Answer  $answer
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Answer $answer, Image $image)
    {
        $answer->images()->where('id',$image->id)->delete();
        return response()->json([
            'message' => 'Image Deleted'
        ]);
    }
}

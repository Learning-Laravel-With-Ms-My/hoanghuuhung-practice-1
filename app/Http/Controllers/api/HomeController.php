<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
/**
 * @OA\Info(
 *     title="My First API",
 *     version="0.1"
 * )
 * */
class HomeController extends Controller
{
        /**
     * @OA\Get(
     *     path="/api/data.json",
     *     @OA\Response(
     *         response="200",
     *         description="The data"
     *     )
     * )
     */
    public function index(Request $request){
        return response()->json(
            [
                'title' => $request->input('title'),
                'message'=>'Welcome'
            ]
        );
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
   private $posts;
   
   public function __construct(){
    $this->posts = new Post();
   }
    public function index()
    {
        $allPost =  $this->posts->all();
        return response()->json($allPost);
    }

    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dataInsert = [
            'title' => $request->title,
            'description' => $request->description
        ];
    
        $insert = $this->posts->insertData($dataInsert);
    
        if ($insert) {
            return response()->json("success", 200);
        } else {
            return response()->json("error", 500);
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = $this->posts->getOne($id);
        if ($data) {
            return response()->json($data);
        } else {
            return response()->json(['message' => 'Post not found'], 404);
        }
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $dataUpdate = [
            'title' => $request->title,
            'description' => $request->description
        ];
        $data = $this->posts->updatePost($id, $dataUpdate);
        if ($data) {
            return response()->json('sucess',200);
        } else {
            return response()->json(['message' => 'Cannot update'], 404);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = $this->posts->deletePost($id);
        if ($data) {
            return response()->json('sucess',200);
        } else {
            return response()->json(['message' => 'Cannot delete'], 404);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
/**
 * @OA\Post(
 *     path="/api/posts",
 *     summary="Create a new post",
 *     description="Create a new post with the provided title and description",
 *     tags={"Post"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title", "description"},
 *             @OA\Property(property="title", type="string", example="New Post Title"),
 *             @OA\Property(property="description", type="string", example="This is a new post description")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\MediaType(
 *             mediaType="application/json"
 *         )
 *     )
 * )
 */


class PostController extends Controller
{
   private $posts;
   
   public function __construct(){
    $this->posts = new Post();
   }
   public function getAllPostsForSwagger()
   {
       // Lấy tất cả các bài viết từ cơ sở dữ liệu
       $posts = Post::all();

       return $posts;
   }

    public function index()
    {
        $allPost =  $this->posts->all();
        return response()->json($allPost);
    }
    public function swagger(Request $request)
    {
        // Xử lý yêu cầu Swagger ở đây
        // Ví dụ: tạo bài viết mới từ dữ liệu được gửi từ Swagger UI

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $post = Post::create($validatedData);

        return response()->json($post, 201);
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

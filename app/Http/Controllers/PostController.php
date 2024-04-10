<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class PostController extends Controller
{
        /**
     * @OA\Get(
     *     path="/api/posts",
     *     summary="Get all posts",
     *     tags={"Posts"},
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearerAuth":{}}}
     * )
     */
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

 public function store(Request $request)
 {

    $validator = Validator::make($request->all(), [
        'title' => 'required|unique:posts|max:100|min:5',
        'description' => 'required|max:50|min:10',
    ], [
    ]);
    if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json($errors, 412);
    }
    $post = new Post();
    $post->title = $request->title;
    $post->description = $request->description;
    $post->user_id = $request->user_id; 
    $post->save();

    // Thành công
    return response()->json("success", 200);
 } 

     /**
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     summary="Get a specific post",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Post ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function show($id)
    {
     // Lấy thông tin về bài đăng với thông tin người tạo
     $post = Post::with('user')->find($id);

     if ($post) {
         // Tạo một mảng kết hợp chứa dữ liệu
         $responseData = [
             'success' => true,
             'data' => [
                 'id' => $post->id,
                 'title' => $post->title,
                 'email' => $post->user->email, // Giả sử email của người tạo là trường email trong model User
                 'description' => $post->description,
                 'create_by' => $post->user->name, // Giả sử tên của người tạo là trường name trong model User
                 'created_at' => $post->created_at,
                 'updated_at' => $post->updated_at
             ]
         ];
 
         // Trả về mảng dữ liệu
         return response()->json($responseData);
     } else {
         // Nếu không tìm thấy bài đăng, trả về thông báo lỗi
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
 * @OA\Put(
 *     path="/api/posts/{id}",
 *     summary="Update a specific post",
 *     tags={"Posts"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Post ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 type="object",
 *                 @OA\Property(property="title", type="string"),
 *                 @OA\Property(property="content", type="string")
 *             )
 *         )
 *     ),
 *     @OA\Response(response="200", description="Success"),
 *     security={{"bearerAuth":{}}}
 * )
 */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:posts|max:100|min:5',
            'description' => 'required|max:50|min:10',
        ], [
            'title.required' => 'Title bắt buộc phải nhập',
            'title.min' => 'Title phải từ :min ký tự trở lên',
            'title.max' => 'Title phải từ :max ký tự trở lên',
            'title.unique' => 'Title đã tồn tại trên hệ thống',
            'description.required' => 'Description bắt buộc phải nhập',
            'description.min' => 'Description phải từ :min ký tự trở lên',
            'description.max' => 'Description phải từ :max ký tự trở lên',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json($errors, 412);
        } 
        $dataUpdate = [
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $request->user_id,
        ];
        $data = $this->posts->updatePost($id, $dataUpdate);
        if ($data) {
            return response()->json('sucess',200);
        } else {
            return response()->json(['message' => 'Cannot update'], 404);
        }

    }

        /**
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     summary="Delete a specific post",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Post ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     security={{"bearerAuth":{}}}
     * )
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

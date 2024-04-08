<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $users;
    public function __construct(){
        $this->users = new Users();
       }
    public function index()
    {
        $allUsers =  $this->users->all();
        return response()->json($allUsers);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dataInsert = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ];
    
        $insert = $this->users->insertData($dataInsert);
    
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
        $data = $this->users->getOne($id);
        if ($data) {
            return response()->json($data);
        } else {
            return response()->json(['message' => 'Post not found'], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Users $users)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $dataUpdate = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ];
        $data = $this->users->updatePost($id, $dataUpdate);
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
        $data = $this->users->deletePost($id);
        if ($data) {
            return response()->json('sucess',200);
        } else {
            return response()->json(['message' => 'Cannot delete'], 404);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
        $allUsers = Users::with('userPhone')->get();

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

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:15',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|confirmed',
            'phone' => 'required|string|unique:phones,phone',
        ], [
            'name.required' => 'Họ và tên bắt buộc phải nhập',
            'name.string' => 'Họ và tên bắt buộc là string',
            'name.min' => 'Họ và tên phải từ :min ký tự trở lên',
            'name.max' => 'Họ và tên phải nhỏ hơn :max ký tự',
            'email.required' => 'Email bắt buộc phải nhập',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại trên hệ thống',
            'email.string' => 'Email bắt buộc là string',
            'password.required' => 'Password bắt buộc phải nhập',
            'password.string' => 'Password bắt buộc là string',
            'password.confirmed' => 'Password xác nhận không đúng',
            'phone.required' => 'Số điện thoại bắt buộc phải nhập',
            'phone.string' => 'Số điện thoại bắt buộc là string',
            'phone.unique' => 'Số điện thoại đã tồn tại trên hệ thống',
        ]);
        
    
        if ($validator->fails()) {
            return response()->json($validator->errors()->all(), 412);
        }
    
        $user = Users::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);
    
        $user->userPhone()->create([
            'phone' => $request->input('phone'),
        ]);
    
        return response()->json("success", 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Users::with('userPhone')->find($id);

        if ($user) {
            return response()->json($user);
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


    public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|min:3|max:15',
        'email' => 'required|string|email|unique:users,email,'.$id,
        'password' => 'required|string|confirmed',
        'phone' => 'required|string|unique:phones,phone',
    ], [
        'name.required' => 'Họ và tên bắt buộc phải nhập',
        'name.string' => 'Họ và tên bắt buộc là string',
        'name.min' => 'Họ và tên phải từ :min ký tự trở lên',
        'name.max' => 'Họ và tên phải nhỏ hơn :max ký tự',
        'email.required' => 'Email bắt buộc phải nhập',
        'email.email' => 'Email không đúng định dạng',
        'email.unique' => 'Email đã tồn tại trên hệ thống',
        'email.string' => 'Email bắt buộc là string',
        'password.required' => 'Password bắt buộc phải nhập',
        'password.string' => 'Password bắt buộc là string',
        'password.confirmed' => 'Password xác nhận không đúng',
        'phone.required' => 'Số điện thoại bắt buộc phải nhập',
        'phone.string' => 'Số điện thoại bắt buộc là string',
        'phone.unique' => 'Số điện thoại đã tồn tại trên hệ thống',
    ]);

    if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json($errors, 412);
    } 

    $user = Users::findOrFail($id);
    $user->name = $request->input('name');
    $user->email = $request->input('email');
    $user->password = bcrypt($request->input('password'));
    $user->save();

    $phone = Phone::where('user_id', $id)->first();
    if ($phone) {
        $phone->phone = $request->input('phone');
        $phone->save();
    } else {
        // Nếu không tìm thấy số điện thoại, có thể tạo mới nếu cần thiết
        Phone::create([
            'user_id' => $id,
            'phone' => $request->input('phone'),
        ]);
    }

    return response()->json("success", 200);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Xóa thông tin số điện thoại của người dùng từ bảng phones
        Phone::where('user_id', $id)->delete();
    
        // Xóa người dùng từ bảng users
        $deleted = Users::destroy($id);
    
        if ($deleted) {
            return response()->json('success', 200);
        } else {
            return response()->json(['message' => 'Cannot delete'], 404);
        }
    }
    
}

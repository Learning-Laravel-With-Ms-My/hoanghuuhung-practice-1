<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Users extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    // public $timestamps = false;
    public function insertData($data){
        return DB::table('users')->insert($data);
    }
    public function getOne($id){
        return DB::table('users')->where('id', $id)->first();
    }
    public function updatePost($id, $data){
        return DB::table('users')->where('id', $id)->update($data);
    }
    public function deletePost($id){
        return DB::table('users')->where('id', $id)->delete();
    }
    public function userPhone()
    {
        return $this->hasOne(Phone::class, 'user_id', 'id');
    }
}

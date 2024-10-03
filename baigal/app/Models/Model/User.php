<?php

namespace App\Models\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class User extends Model
{
    use HasFactory;

    protected $table = 'admin';

    protected $fillable = [
        'username',
        'password',
        'type_code'
    ];

    // public function userInsert($id, $data)
    // {
    //     try {
    //     DB::table('admin')->where('id',$id)->update($data);
    //     } catch (\Illuminate\Database\QueryException $ex) {
    //         return false;
    //     }
    // }

    // public function userUpdate($data)
    // {
    //     try {
    //         DB::table('admin')->insert($data);
    //         return true;
    //     } catch (\Illuminate\Database\QueryException $ex) {
    //         return false;
    //     }
    // }

    public function userSelect()
    {
        try {
            return DB::table('admin')->get();
        } catch (\Illuminate\Database\QueryException $ex) {
            return [];
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Post extends Model
{
    use HasFactory;

    public function postInsert($data)
    {
        $insertedId = null;
        try {
            $insertedId = DB::table('posts')->insertGetId($data);
        } catch(\Illuminate\Database\QueryException $ex){
            return false;
        }
        return $insertedId;
    }

    public function postUpdate($id, $data)
        {
            try {
                DB::table('posts')->where('id', $id)->update($data);
            } catch(\Illuminate\Database\QueryException $ex){
                return false;
            }
            return true;
        }

    public function postImageInsert($data)
    {
        try {
            DB::table('post_images')->insert($data);
        } catch(\Illuminate\Database\QueryException $ex){
            return false;
        }
        return true;
    }

    public function allPostsCount()
    {
        $postsCount = [
            'all' => 0,
        ];

        try {
            $allPostsCount = DB::table('posts')->count();

            $postsCount['all'] = $allPostsCount;
        } catch(\Illuminate\Database\QueryException $ex){
            return $postsCount;
        }

        return $postsCount;
    }

    public function postsSelect($id)
    {
        $status = [];
        try {
            $status = DB::table('posts')->select('id', 'status', 'admin_comment')->whereIn('id', $id)->get();
        } catch(\Illuminate\Database\QueryException $ex){
            return $status;
        }
        return $status;
    }


    public function getTypeNames()
    {
        $typeName = [];
        try {
            $typeName = DB::table('type')->get();
        } catch(\Illuminate\Database\QueryException $ex){
            return $typeName;
        }
        return $typeName;
    }

    public function getStatusNames()
    {
        $statusName = [];
        try {
            $statusName = DB::table('status')->get();
        } catch(\Illuminate\Database\QueryException $ex){
            return $statusName;
        }
        return $statusName;
    }
}

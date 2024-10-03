<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Admin extends Model
{
    use HasFactory;

    public function adminLogin($username, $password)
    {
        try {
            $admin = DB::table('admin')
                ->where('username', $username)
                ->where('password', $password)
                ->get();
            return $admin;
        } catch(\Illuminate\Database\QueryException $ex){
            return false;
        }
        return false;
    }

    public function postSelect($check1, $check2, $check3, $check4, $tcode)
    {
        $posts = [];
        try {
            $posts = Post::select('posts.*', 'status.name AS status_name', 'type.name AS type_name')
                        ->join('status', 'posts.status', '=', 'status.status_code')
                        ->join('type', 'posts.type', '=', 'type.type_code');

            if ($check1) {
                $posts->orWhere('status', 1);
            }
            if ($check2) {
                $posts->orWhere('status', 2);
            }
            if ($check3) {
                $posts->orWhere('status', 3);
            }
            if ($check4) {
                $posts->orWhere('status', 4);
            }
            if($tcode > 0) {
                $posts->where('type_code', $tcode);
            }
            $posts = $posts->orderBy('created_at', 'desc')->get();
        } catch(\Illuminate\Database\QueryException $ex){
            $posts = [];
        }
        return $posts;
    }

    public function postSingleSelect($id){
        $post = [];
        try {
            $post = DB::table('posts')->where('id', $id)->first();
        } catch(\Illuminate\Database\QueryException $ex){
            $post;
        }
        return $post;
    }

    public function imagePathSelect($id){
        $post = [];
        try {
            $post = DB::table('post_images')->where('post_id', $id)->get();
        } catch(\Illuminate\Database\QueryException $ex){
            $post;
        }
        return $post;
    }

    public function imagePathAll(){
        $post = [];
        try {
            $post = DB::table('post_images')->get();
        } catch(\Illuminate\Database\QueryException $ex){
            $post;
        }
        return $post;
    }

    public function getCountsByStatus($tcode) {
        if($tcode == 0) {
            $counts = [
                'new' => DB::table('posts')->where('type', 1)->count(),
                'received' => DB::table('posts')->where('status', 2)->count(),
                'resolved' => DB::table('posts')->where('status', 3)->count(),
                'rejected' => DB::table('posts')->where('status', 4)->count(),
            ];
        } else {
            $counts = [
                'new' => DB::table('posts')->where('status', 1)->where('type', $tcode)->count(),
                'received' => DB::table('posts')->where('status', 2)->where('type', $tcode)->count(),
                'resolved' => DB::table('posts')->where('status', 3)->where('type', $tcode)->count(),
                'rejected' => DB::table('posts')->where('status', 4)->where('type', $tcode)->count(),
            ];
        }
        return $counts;
    }

    public function getTypeCount() {
        $typeCounts = [
            'Бусад' => DB::table('posts')->where('type', 1)->count(),
            'Хог хягдал' => DB::table('posts')->where('type', 2)->count(),
            'эвдрэл доройтол' => DB::table('posts')->where('type', 3)->count(),
            'Бохир' => DB::table('posts')->where('type', 4)->count(),
        ];
        return $typeCounts;
    }
}

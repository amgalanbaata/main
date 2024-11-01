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
                ->where('email', $username)
                ->where('password', $password)
                ->get();
            return $admin;
        } catch(\Illuminate\Database\QueryException $ex){
            return false;
        }
        return false;
    }
    public function postSelectType($type)
    {
        $posts  = [];
        try {
            $posts = Post::select('posts.*')
                         ->where('type', $type)
                         ->orderBy('created_at', 'desc')
                         ->get();
        } catch (\Illuminate\Database\QueryException $ex) {
            $posts = [];
        }

        return $posts;
    }


    public function postSelect($check1, $check2, $check3, $check4, $check5, $check6, $tcode)
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
            if ($check5) {
                $posts->orWhere('status', 5);
            }
            if ($check6) {
                $posts->orWhere('status', 6);
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
                'new' => DB::table('posts')->where('status', 1)->count(),
                'Duplicated' => DB::table('posts')->where('status', 2)->count(),
                'Additional information is required' => DB::table('posts')->where('status', 3)->count(),
                'Refused' => DB::table('posts')->where('status', 4)->count(),
                'Conduct_soil_analysis' => DB::table('posts')->where('status', 5)->count(),
                'Register_directly_on_location' => DB::table('posts')->where('status', 6)->count(),
            ];
        } else {
            $counts = [
                'new' => DB::table('posts')->where('type', $tcode)->where('status', 1)->count(),
                'Duplicated' => DB::table('posts')->where('type', $tcode)->where('status', 2)->count(),
                'Additional information is required' => DB::table('posts')->where('type', $tcode)->where('status', 3)->count(),
                'Refused' => DB::table('posts')->where('type', $tcode)->where('status', 4)->count(),
                'Conduct_soil_analysis' => DB::table('posts')->where('type', $tcode)->where('status', 5)->count(),
                'Register_directly_on_location' => DB::table('posts')->where('type', $tcode)->where('status', 6)->count(),
            ];
        }
        return $counts;
    }

    public function getCountsByStatusReport($tcode) {
        if($tcode == 0) {
            $counts = [
                'new' => DB::table('posts')->where('status', 1)->count(),
                'Duplicated' => DB::table('posts')->where('status', 2)->count(),
                'Additional information is required' => DB::table('posts')->where('status', 3)->count(),
                'Refused' => DB::table('posts')->where('status', 4)->count(),
                'Conduct_soil_analysis' => DB::table('posts')->where('status', 5)->count(),
                'Register_directly_on_location' => DB::table('posts')->where('status', 6)->count(),
            ];
        } else {
            $counts = [
                'new' => DB::table('posts')->where('type', $tcode)->where('status', 1)->count(),
                'Duplicated' => DB::table('posts')->where('type', $tcode)->where('status', 2)->count(),
                'Additional information is required' => DB::table('posts')->where('type', $tcode)->where('status', 3)->count(),
                'Refused' => DB::table('posts')->where('type', $tcode)->where('status', 4)->count(),
                'Conduct_soil_analysis' => DB::table('posts')->where('type', $tcode)->where('status', 5)->count(),
                'Register_directly_on_location' => DB::table('posts')->where('type', $tcode)->where('status', 6)->count(),
            ];
        }
        return $counts;
    }

    public function getAgreedCounts() {
        $counts = [];
        try {
            $counts = DB::table('posts')->where('agreed', 'Зөвшөөрсөн')->count();
        } catch(\Illuminate\Database\QueryException $ex){
           return $counts;
        }
        return $counts;
    }

    public function getNewCounts($adminType) {
        $counts = [];
        try {
            $counts = DB::table('posts')->where('status', 1)
            ->where('type', $adminType)
            ->count();
        } catch(\Illuminate\Database\QueryException $ex){
           return $counts;
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

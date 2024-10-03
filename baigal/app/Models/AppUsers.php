<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class AppUsers extends Model
{
    use HasFactory;

    public function appUsers()
    {
        $responce = [];
        try {
            $responce = DB::table('app_users')->get();
        } catch(\Illuminate\Database\QueryException $ex){
            return $responce;
        }
        return $responce;
    }
}

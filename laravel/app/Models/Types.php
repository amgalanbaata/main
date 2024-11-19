<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Types extends Model
{
    use HasFactory;

    public function select($id)
    {
        $types = [];
        try {
            $types = DB::table('type_categories')
                ->where('type_id', $id)
                ->get();
            return $types;
        } catch(\Illuminate\Database\QueryException $ex){
            return [];
        }
        return [];
    }

    public function selectCat($id)
    {
        $types = [];
        try {
            $types = DB::table('type_categories')
                ->where('id', $id)
                ->get();
            return $types;
        } catch(\Illuminate\Database\QueryException $ex){
            return [];
        }
        return [];
    }
}

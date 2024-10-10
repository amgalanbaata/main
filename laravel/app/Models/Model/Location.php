<?php

namespace App\Models\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'comment',
        'latitude',
        'longitude',
        'color',
    ];

    public function getLocation()
    {
        $data = [];
        try {
            $data = DB::table('locations')->get();
            return $data;
        } catch (\Illuminate\Database\QueryException $ex) {
            return $data;
        }
    }

    // public function locationSave(array $data)
    // {
    //     try {
    //         return DB::table('location')->insert($data);
    //     } catch (\Illuminate\Database\QueryException $ex) {
    //         return ;
    //     }
    // }
}

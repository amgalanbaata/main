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
        'pdf_path'
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

    public function locationSingleSelect($latitude, $longitude)
    {
        $location = [];
        try {
            $location = DB::table('locations')->where('latitude', $latitude)->where('longitude', $longitude)->first();
        } catch(\Illuminate\Database\QueryException $ex){
            $location;
        }
        // dd($location);
        return $location;
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

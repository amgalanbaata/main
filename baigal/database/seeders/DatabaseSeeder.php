<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = [
            [
                'username' => 'admin@gmail.com',
                'password' => 'admin@1234',
                'type_code' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        DB::table('admin')->insert($admin);
        $type = [
            [
                'type_code' => 1,
                'name' => 'Бусад'
            ],
            [
                'type_code' => 2,
                'name' => 'Хог хягдал'
            ],
            [
                'type_code' => 3,
                'name' => 'эвдрэл доройтол'
            ],
            [
                'type_code' => 4,
                'name' => 'Бохир'
            ],
        ];
        DB::table('type')->insert($type);
        $status = [
            [
                'status_code' => 1,
                'name' => 'Шинээр ирсэн'
            ],
            [
                'status_code' => 2,
                'name' => 'Хүлээн авсан'
            ],
            [
                'status_code' => 3,
                'name' => 'Шийдвэрлэсэн'
            ],
            [
                'status_code' => 4,
                'name' => 'Татгалзсан'
            ],
        ];
        DB::table('status')->insert($status);
    }
}

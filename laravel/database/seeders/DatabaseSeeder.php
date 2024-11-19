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
                'name' => 'Эвдрэл доройтол'
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
                'name' => 'Давхардсан'
            ],
            [
                'status_code' => 3,
                'name' => 'Нэмэлт мэдээлэл шаардлагатай'
            ],
            [
                'status_code' => 4,
                'name' => 'Буцаасан'
            ],
            [
                'status_code' => 5,
                'name' => 'Хөрсний шинжилгээ хийх'
            ],
            [
                'status_code' => 6,
                'name' => 'Байршилд шууд бүртгэх'
            ],
        ];
        DB::table('status')->insert($status);

        $type_category = [
            [
                'type_id' => 2,
                'name' => 'Ахуйн хог'
            ],
            [
                'type_id' => 2,
                'name' => 'Барилгын хог'
            ],
            [
                'type_id' => 2,
                'name' => 'Үйлдвэрийн хог'
            ],
            [
                'type_id' => 2,
                'name' => 'Хөдөө аж ахуйн хог'
            ],
            [
                'type_id' => 2,
                'name' => 'Эмнэлгийн хог'
            ],
            [
                'type_id' => 2,
                'name' => 'Авто техникийн хог'
            ],
            [
                'type_id' => 3,
                'name' => 'Хүнээс үүдэлтэй'
            ],
            [
                'type_id' => 3,
                'name' => 'Техник төхөөрөмж'
            ],
            [
                'type_id' => 3,
                'name' => 'Ан амьтан'
            ],
            [
                'type_id' => 3,
                'name' => 'Байгалийн'
            ],
            [
                'type_id' => 4,
                'name' => 'Бохир ус'
            ],
            [
                'type_id' => 4,
                'name' => 'Химийн элемент'
            ],
            [
                'type_id' => 4,
                'name' => 'Шатахуун тос'
            ],
        ];
        DB::table('type_categories')->insert($type_category);
    }
}

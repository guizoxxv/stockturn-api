<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Upload;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            Upload::create([
                'path' => 'uploads/upload_sample.csv',
                'size' => 10,
                'type' => 'application/csv',
                'status' => 'CREATED',
            ]);

            Product::factory(30)->create();
        });
    }
}

<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // import file propinsi.json
        $file = file_get_contents(base_path('/database/provinsi.json'));
        // decode data json dan jadikan array
        $data = json_decode($file, true);

        // insert ke database lewat model
        Province::insert($data);
    }
}

<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // import file propinsi.json
        $fileKota       = file_get_contents(base_path('/database/kota.json'));
        $fileKabupaten  = file_get_contents(base_path('/database/kabupaten.json'));
        // decode data json dan jadikan array
        $dataKota       = json_decode($fileKota, true);
        $dataKabupaten  = json_decode($fileKabupaten, true);

        // insert ke database lewat model
        City::insert($dataKota);
        City::insert($dataKabupaten);
    }
}

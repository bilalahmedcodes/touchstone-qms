<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SolarLtCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('solar_lt_categories')->truncate();
        $json = File::get("database/data/solarlt-categories.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            DB::table('solar_lt_categories')->insert(array(
                'name' => $obj->name,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ));
        }
    }
}

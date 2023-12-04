<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DatapointCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/datapoint-categories.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            DB::table('datapoint_categories')->insert(array(
                "voice_evaluation_id"=> $obj->voice_evaluation_id,
                "campaign_id"=> $obj->campaign_id,
                "project_id"=> $obj->project_id,
                'name' => $obj->name,
            ));
        }
    }
}

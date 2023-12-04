<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DataPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/datapoints.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            DB::table('datapoints')->insert(array(
                "voice_evaluation_id" => $obj->voice_evaluation_id,
        "datapoint_category_id"=>$obj->datapoint_category_id,
        "project_id"=>$obj->project_id,
        "campaign_id"=>$obj->campaign_id,
                'name' => $obj->name,
                'question' => $obj->question,
                'score' => $obj->score,
                'sort' => 1,
            ));
        }
    }
}


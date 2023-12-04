<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class VoiceEvaluationActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/voice-evaluation-actions.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            DB::table('voice_evaluation_actions')->insert(array(
                'name' => $obj->name,
                'sort' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ));
        }
    }
}

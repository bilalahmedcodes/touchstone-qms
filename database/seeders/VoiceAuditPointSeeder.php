<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class VoiceAuditPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/data-points.json");
        $data = json_decode($json);

        for ($audit=1; $audit < 251; $audit++) {
            # code...
            $point = 0;
            foreach ($data as $obj) {
                $point++;
                DB::table('voice_audit_points')->insert(array(
                    'voice_audit_id' => $audit,
                    'datapoint_category_id' => $obj->category_id,
                    'datapoint_id' => $point,
                    'answer' => 1,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ));
            }
        }


    }
}

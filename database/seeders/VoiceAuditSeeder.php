<?php

namespace Database\Seeders;

use App\Models\VoiceAudit;
use Illuminate\Database\Seeder;

class VoiceAuditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VoiceAudit::factory()->count(250)->create();
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call([
            RoleSeeder::class,
            DatapointCategorySeeder::class,
            DataPointSeeder::class,
            SolarLtCategorySeeder::class,
            SolarLtDatapointSeeder::class,
            VoiceEvaluationSeeder::class,
            // VoiceAuditSeeder::class,
            VoiceAuditPointSeeder::class,
            VoiceEvaluationActionSeeder::class,
        ]);
    }
}

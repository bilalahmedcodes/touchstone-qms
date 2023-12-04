<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(array(
            'hrms_id' => 123456,
            'name' => 'Administrator',
            'email' => 'admin@touchstone.com.pk',
            'password' => Hash::make('Touchstone@786'),
            'password_text' => 'Touchstone@786',
            'status' => 'active',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ));
    }
}

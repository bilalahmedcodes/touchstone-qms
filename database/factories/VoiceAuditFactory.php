<?php

namespace Database\Factories;

use App\Models\VoiceAudit;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoiceAuditFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VoiceAudit::class;


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'voice_evaluation_id' => 1,
            'user_id' => $this->faker->numberBetween($min = 3, $max = 20),
            'associate_id' => $this->faker->numberBetween($min = 21, $max = 500),
            'campaign_id' => $this->faker->numberBetween($min = 1, $max = 150),
            'call_date' => now(),
            'percentage' => $this->faker->randomFloat($nbMaxDecimals = NULL, $min = 50, $max = 98),
            'customer_name' => $this->faker->name(),
            'customer_phone' => $this->faker->phoneNumber(),
            'record_id' => $this->faker->numberBetween($min = 1000, $max = 9000),
            'recording_duration' => '00:20:01',
            'recording_link' => $this->faker->imageUrl($width = 640, $height = 480),
            'outcome' => $this->faker->randomElement($array = array ('accepted','rejected')),
            'notes' => $this->faker->text($maxNbChars = 100),
            'evaluation_time' => '00:04:25'
        ];
    }
}

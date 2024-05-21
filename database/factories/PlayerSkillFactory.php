<?php

namespace Database\Factories;

use App\Models\PlayerSkill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlayerSkill>
 */
class PlayerSkillFactory extends Factory
{
    protected $model = PlayerSkill::class;

    public function definition()
    {
        return [
            'skill' => $this->faker->randomElement(['speed', 'strength', 'stamina']),
            'value' => $this->faker->numberBetween(50, 100)
        ];
    }
}

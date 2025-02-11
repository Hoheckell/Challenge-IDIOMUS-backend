<?php

namespace Database\Factories;

use App\Enums\PlayerPosition;
use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    protected $model = Player::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'position' => $this->faker->randomElement(PlayerPosition::getValues())
        ];
    }
}

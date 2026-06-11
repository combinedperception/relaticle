<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\AgentRun;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AgentRun>
 */
final class AgentRunFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'user_id' => User::factory(),
            'status' => $this->faker->randomElement(['running', 'completed', 'failed']),
            'steps' => [],
            'summary' => $this->faker->sentence(),
        ];
    }
}

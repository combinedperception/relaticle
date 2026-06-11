<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\McpToolInvocationLog;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<McpToolInvocationLog>
 */
final class McpToolInvocationLogFactory extends Factory
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
            'tool_name' => $this->faker->slug(2),
            'duration_ms' => $this->faker->numberBetween(1, 5000),
        ];
    }
}

<?php

declare(strict_types=1);

use App\Filament\Resources\PeopleResource;
use App\Models\People;
use App\Models\User;

mutates(PeopleResource::class);

it('can create a person through the browser', function (): void {
    $user = User::factory()->withTeam()->create();
    $team = $user->ownedTeams()->first();

    $this->visit('/app/login')
        ->type('[id="form.email"]', $user->email)
        ->type('[id="form.password"]', 'password')
        ->click('button.fi-btn')
        ->assertPathIs("/app/{$team->slug}/companies")
        ->navigate("/app/{$team->slug}/people")
        ->press('New person')
        ->type('[id="mountedActionSchema0.name"]', 'Browser Test Person')
        ->press('Create')
        ->assertSee('Browser Test Person');

    expect(People::where('name', 'Browser Test Person')->where('team_id', $team->id)->exists())->toBeTrue();
});

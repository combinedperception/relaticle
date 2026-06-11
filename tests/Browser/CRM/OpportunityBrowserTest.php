<?php

declare(strict_types=1);

use App\Filament\Resources\OpportunityResource;
use App\Models\Opportunity;
use App\Models\User;

mutates(OpportunityResource::class);

it('can create an opportunity through the browser', function (): void {
    $user = User::factory()->withTeam()->create();
    $team = $user->ownedTeams()->first();

    $this->visit('/app/login')
        ->type('[id="form.email"]', $user->email)
        ->type('[id="form.password"]', 'password')
        ->click('button.fi-btn')
        ->assertPathIs("/app/{$team->slug}/companies")
        ->navigate("/app/{$team->slug}/opportunities")
        ->press('New opportunity')
        ->type('[id="mountedActionSchema0.name"]', 'Browser Test Deal')
        ->press('Create')
        ->assertSee('Browser Test Deal');

    expect(Opportunity::where('name', 'Browser Test Deal')->where('team_id', $team->id)->exists())->toBeTrue();
});

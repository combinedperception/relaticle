<?php

declare(strict_types=1);

use App\Filament\Resources\NoteResource;
use App\Models\Note;
use App\Models\User;

mutates(NoteResource::class);

it('can create a note through the browser', function (): void {
    $user = User::factory()->withTeam()->create();
    $team = $user->ownedTeams()->first();

    $this->visit('/app/login')
        ->type('[id="form.email"]', $user->email)
        ->type('[id="form.password"]', 'password')
        ->click('button.fi-btn')
        ->assertPathIs("/app/{$team->slug}/companies")
        ->navigate("/app/{$team->slug}/notes")
        ->press('New note')
        ->type('[id="mountedActionSchema0.title"]', 'Browser Test Note')
        ->press('Create')
        ->assertSee('Browser Test Note');

    expect(Note::where('title', 'Browser Test Note')->where('team_id', $team->id)->exists())->toBeTrue();
});

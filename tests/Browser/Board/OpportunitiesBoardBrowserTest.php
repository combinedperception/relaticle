<?php

declare(strict_types=1);

use App\Enums\CustomFields\OpportunityField;
use App\Filament\Pages\OpportunitiesBoard;
use App\Models\CustomField;
use App\Models\Opportunity;
use App\Models\User;

mutates(OpportunitiesBoard::class);

it('can create an opportunity from the board', function (): void {
    $user = User::factory()->withTeam()->create();
    $team = $user->ownedTeams()->first();

    $this->visit('/app/login')
        ->type('[id="form.email"]', $user->email)
        ->type('[id="form.password"]', 'password')
        ->click('button.fi-btn')
        ->assertPathIs("/app/{$team->slug}/companies")
        ->navigate("/app/{$team->slug}/opportunities-board")
        ->click('.flowforge-column:first-child [aria-label="Add Opportunity"]')
        ->type('[id="mountedActionSchema0.name"]', 'Board Browser Deal')
        ->press('Create')
        ->assertSee('Board Browser Deal');

    expect(Opportunity::where('name', 'Board Browser Deal')->where('team_id', $team->id)->exists())->toBeTrue();
});

it('can edit an opportunity name from the board', function (): void {
    $user = User::factory()->withTeam()->create();
    $team = $user->ownedTeams()->first();

    $stageField = CustomField::query()->forEntity(Opportunity::class)->where('code', OpportunityField::STAGE)->first();
    $opportunity = Opportunity::factory()->recycle([$user, $team])->create(['name' => 'Original Deal']);
    $opportunity->saveCustomFieldValue($stageField, $stageField->options->first()->getKey());

    $this->visit('/app/login')
        ->type('[id="form.email"]', $user->email)
        ->type('[id="form.password"]', 'password')
        ->click('button.fi-btn')
        ->assertPathIs("/app/{$team->slug}/companies")
        ->navigate("/app/{$team->slug}/opportunities-board")
        ->click('[aria-label="Actions"]')
        ->press('Edit')
        ->clear('[id="mountedActionSchema0.name"]')
        ->type('[id="mountedActionSchema0.name"]', 'Updated Deal')
        ->press('Submit')
        ->assertSee('Updated Deal')
        ->assertDontSee('Original Deal');

    expect(Opportunity::where('name', 'Updated Deal')->where('team_id', $team->id)->exists())->toBeTrue();
});

it('can delete an opportunity from the board', function (): void {
    $user = User::factory()->withTeam()->create();
    $team = $user->ownedTeams()->first();

    $stageField = CustomField::query()->forEntity(Opportunity::class)->where('code', OpportunityField::STAGE)->first();
    $opportunity = Opportunity::factory()->recycle([$user, $team])->create(['name' => 'Deal To Delete']);
    $opportunity->saveCustomFieldValue($stageField, $stageField->options->first()->getKey());

    $this->visit('/app/login')
        ->type('[id="form.email"]', $user->email)
        ->type('[id="form.password"]', 'password')
        ->click('button.fi-btn')
        ->assertPathIs("/app/{$team->slug}/companies")
        ->navigate("/app/{$team->slug}/opportunities-board")
        ->click('[aria-label="Actions"]')
        ->press('Delete')
        ->press('Confirm')
        ->assertDontSee('Deal To Delete');

    expect(Opportunity::withTrashed()->find($opportunity->id)?->deleted_at)->not->toBeNull();
});

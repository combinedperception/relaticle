<?php

declare(strict_types=1);

use App\Enums\CustomFields\TaskField;
use App\Filament\Pages\TasksBoard;
use App\Models\CustomField;
use App\Models\Task;
use App\Models\User;

mutates(TasksBoard::class);

it('can create a task from the board', function (): void {
    $user = User::factory()->withTeam()->create();
    $team = $user->ownedTeams()->first();

    $this->visit('/app/login')
        ->type('[id="form.email"]', $user->email)
        ->type('[id="form.password"]', 'password')
        ->click('button.fi-btn')
        ->assertPathIs("/app/{$team->slug}/companies")
        ->navigate("/app/{$team->slug}/tasks-board")
        ->click('.flowforge-column:first-child [aria-label="Add Task"]')
        ->type('[id="mountedActionSchema0.title"]', 'Board Browser Task')
        ->press('Create')
        ->assertSee('Board Browser Task');

    expect(Task::where('title', 'Board Browser Task')->where('team_id', $team->id)->exists())->toBeTrue();
});

it('can edit a task title from the board', function (): void {
    $user = User::factory()->withTeam()->create();
    $team = $user->ownedTeams()->first();

    $statusField = CustomField::query()->forEntity(Task::class)->where('code', TaskField::STATUS)->first();
    $task = Task::factory()->recycle([$user, $team])->create(['title' => 'Original Title']);
    $task->saveCustomFieldValue($statusField, $statusField->options->firstWhere('name', 'To do')->getKey());

    $this->visit('/app/login')
        ->type('[id="form.email"]', $user->email)
        ->type('[id="form.password"]', 'password')
        ->click('button.fi-btn')
        ->assertPathIs("/app/{$team->slug}/companies")
        ->navigate("/app/{$team->slug}/tasks-board")
        ->click('[aria-label="Actions"]')
        ->press('Edit')
        ->clear('[id="mountedActionSchema0.title"]')
        ->type('[id="mountedActionSchema0.title"]', 'Updated Title')
        ->press('Submit')
        ->assertSee('Updated Title')
        ->assertDontSee('Original Title');

    expect(Task::where('title', 'Updated Title')->where('team_id', $team->id)->exists())->toBeTrue();
});

it('can delete a task from the board', function (): void {
    $user = User::factory()->withTeam()->create();
    $team = $user->ownedTeams()->first();

    $statusField = CustomField::query()->forEntity(Task::class)->where('code', TaskField::STATUS)->first();
    $task = Task::factory()->recycle([$user, $team])->create(['title' => 'Task To Delete']);
    $task->saveCustomFieldValue($statusField, $statusField->options->firstWhere('name', 'To do')->getKey());

    $this->visit('/app/login')
        ->type('[id="form.email"]', $user->email)
        ->type('[id="form.password"]', 'password')
        ->click('button.fi-btn')
        ->assertPathIs("/app/{$team->slug}/companies")
        ->navigate("/app/{$team->slug}/tasks-board")
        ->click('[aria-label="Actions"]')
        ->press('Delete')
        ->press('Confirm')
        ->assertDontSee('Task To Delete');

    expect(Task::withTrashed()->find($task->id)?->deleted_at)->not->toBeNull();
});

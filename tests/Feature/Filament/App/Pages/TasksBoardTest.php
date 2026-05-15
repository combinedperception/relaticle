<?php

declare(strict_types=1);

use App\Enums\CustomFields\TaskField;
use App\Filament\Pages\TasksBoard;
use App\Models\CustomField;
use App\Models\Task;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Relaticle\Flowforge\Board;

mutates(TasksBoard::class);

beforeEach(function () {
    $this->user = User::factory()->withTeam()->create();
    $this->actingAs($this->user);

    $this->team = $this->user->currentTeam;
    Filament::setTenant($this->team);

    $this->statusField = CustomField::query()
        ->forEntity(Task::class)
        ->where('code', TaskField::STATUS)
        ->first();
});

function getTaskBoard(): Board
{
    $component = livewire(TasksBoard::class);

    return $component->instance()->getBoard();
}

it('can render the board page', function (): void {
    livewire(TasksBoard::class)
        ->assertOk();
});

it('displays tasks in the correct board columns', function (): void {
    $todo = $this->statusField->options->firstWhere('name', 'To do');
    $done = $this->statusField->options->firstWhere('name', 'Done');

    $todoTask = Task::factory()->recycle([$this->user, $this->team])->create();
    $todoTask->saveCustomFieldValue($this->statusField, $todo->getKey());

    $doneTask = Task::factory()->recycle([$this->user, $this->team])->create();
    $doneTask->saveCustomFieldValue($this->statusField, $done->getKey());

    $board = getTaskBoard();

    expect($board->getBoardRecords((string) $todo->getKey())->pluck('id'))
        ->toContain($todoTask->id)
        ->and($board->getBoardRecords((string) $done->getKey())->pluck('id'))
        ->toContain($doneTask->id);
});

it('does not show tasks from other teams', function (): void {
    $otherUser = User::factory()->withTeam()->create();
    $otherTask = Task::factory()->for($otherUser->currentTeam)->create();

    $board = getTaskBoard();
    $allRecordIds = collect($this->statusField->options)
        ->flatMap(fn ($opt) => $board->getBoardRecords((string) $opt->getKey()))
        ->pluck('id');

    expect($allRecordIds)->not->toContain($otherTask->id);
});

it('renders the board when a task has multiple assignees', function (): void {
    $todo = $this->statusField->options->firstWhere('name', 'To do');

    $task = Task::factory()->recycle([$this->user, $this->team])->create();
    $task->saveCustomFieldValue($this->statusField, $todo->getKey());

    $secondMember = User::factory()->create();
    $this->team->users()->attach($secondMember);
    $task->assignees()->attach([$this->user->id, $secondMember->id]);

    livewire(TasksBoard::class)->assertOk();
});

it('moves a card between columns via moveCard', function (): void {
    $todo = $this->statusField->options->firstWhere('name', 'To do');
    $inProgress = $this->statusField->options->firstWhere('name', 'In progress');

    $task = Task::factory()->recycle([$this->user, $this->team])->create();
    $task->saveCustomFieldValue($this->statusField, $todo->getKey());

    livewire(TasksBoard::class)
        ->call('moveCard', (string) $task->id, (string) $inProgress->getKey())
        ->assertDispatched('kanban-card-moved');

    $updatedValue = $task->fresh()->customFieldValues()
        ->where('custom_field_id', $this->statusField->getKey())
        ->value($this->statusField->getValueColumn());

    expect($updatedValue)->toBe($inProgress->getKey());
});

it('edit card action updates the task title', function (): void {
    $task = Task::factory()->recycle([$this->user, $this->team])->create(['title' => 'Old Title']);

    livewire(TasksBoard::class)
        ->callAction(
            TestAction::make('edit')->arguments(['recordKey' => $task->getKey()]),
            data: ['title' => 'New Title'],
        )
        ->assertHasNoActionErrors();

    expect($task->fresh()->title)->toBe('New Title');
});

it('edit card action preserves existing custom field values when title changes', function (): void {
    $priorityField = CustomField::query()->forEntity(Task::class)->where('code', TaskField::PRIORITY)->first();
    $highOption = $priorityField->options->firstWhere('name', 'High');

    $task = Task::factory()->recycle([$this->user, $this->team])->create();
    $task->saveCustomFieldValue($priorityField, $highOption->getKey());

    livewire(TasksBoard::class)
        ->callAction(
            TestAction::make('edit')->arguments(['recordKey' => $task->getKey()]),
            data: ['title' => 'Updated Title'],
        )
        ->assertHasNoActionErrors();

    $value = $task->fresh()->customFieldValues()
        ->where('custom_field_id', $priorityField->getKey())
        ->value($priorityField->getValueColumn());

    expect($value)->toBe($highOption->getKey());
});

it('edit card action syncs assignees', function (): void {
    $task = Task::factory()->recycle([$this->user, $this->team])->create();
    $task->assignees()->attach($this->user->id);

    $newAssignee = User::factory()->create();
    $this->team->users()->attach($newAssignee);

    livewire(TasksBoard::class)
        ->callAction(
            TestAction::make('edit')->arguments(['recordKey' => $task->getKey()]),
            data: [
                'title' => $task->title,
                'assignees' => [$newAssignee->id],
            ],
        )
        ->assertHasNoActionErrors();

    $assigneeIds = $task->fresh()->assignees->pluck('id');
    expect($assigneeIds)
        ->toContain($newAssignee->id)
        ->not->toContain($this->user->id);
});

it('delete card action soft-deletes the task', function (): void {
    $task = Task::factory()->recycle([$this->user, $this->team])->create();

    livewire(TasksBoard::class)
        ->callAction(
            TestAction::make('delete')->arguments(['recordKey' => $task->getKey()]),
        )
        ->assertHasNoActionErrors();

    $this->assertSoftDeleted($task);
});

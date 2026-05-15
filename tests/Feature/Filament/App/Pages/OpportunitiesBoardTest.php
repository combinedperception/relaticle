<?php

declare(strict_types=1);

use App\Enums\CustomFields\OpportunityField;
use App\Filament\Pages\OpportunitiesBoard;
use App\Models\CustomField;
use App\Models\Opportunity;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Relaticle\Flowforge\Board;

mutates(OpportunitiesBoard::class);

beforeEach(function () {
    $this->user = User::factory()->withTeam()->create();
    $this->actingAs($this->user);

    $this->team = $this->user->currentTeam;
    Filament::setTenant($this->team);

    $this->stageField = CustomField::query()
        ->forEntity(Opportunity::class)
        ->where('code', OpportunityField::STAGE)
        ->first();
});

function getOpportunityBoard(): Board
{
    $component = livewire(OpportunitiesBoard::class);

    return $component->instance()->getBoard();
}

it('can render the board page', function (): void {
    livewire(OpportunitiesBoard::class)
        ->assertOk();
});

it('displays opportunities in the correct board columns', function (): void {
    $prospecting = $this->stageField->options->firstWhere('name', 'Prospecting');
    $closedWon = $this->stageField->options->firstWhere('name', 'Closed Won');

    $prospectingOpportunity = Opportunity::factory()->recycle([$this->user, $this->team])->create();
    $prospectingOpportunity->saveCustomFieldValue($this->stageField, $prospecting->getKey());

    $closedWonOpportunity = Opportunity::factory()->recycle([$this->user, $this->team])->create();
    $closedWonOpportunity->saveCustomFieldValue($this->stageField, $closedWon->getKey());

    $board = getOpportunityBoard();

    expect($board->getBoardRecords((string) $prospecting->getKey())->pluck('id'))
        ->toContain($prospectingOpportunity->id)
        ->and($board->getBoardRecords((string) $closedWon->getKey())->pluck('id'))
        ->toContain($closedWonOpportunity->id);
});

it('does not show opportunities from other teams', function (): void {
    $otherUser = User::factory()->withTeam()->create();
    $otherOpportunity = Opportunity::factory()->for($otherUser->currentTeam)->create();

    $board = getOpportunityBoard();
    $allRecordIds = collect($this->stageField->options)
        ->flatMap(fn ($opt) => $board->getBoardRecords((string) $opt->getKey()))
        ->pluck('id');

    expect($allRecordIds)->not->toContain($otherOpportunity->id);
});

it('moves a card between columns via moveCard', function (): void {
    $prospecting = $this->stageField->options->firstWhere('name', 'Prospecting');
    $qualification = $this->stageField->options->firstWhere('name', 'Qualification');

    $opportunity = Opportunity::factory()->recycle([$this->user, $this->team])->create();
    $opportunity->saveCustomFieldValue($this->stageField, $prospecting->getKey());

    livewire(OpportunitiesBoard::class)
        ->call('moveCard', (string) $opportunity->id, (string) $qualification->getKey())
        ->assertDispatched('kanban-card-moved');

    $updatedValue = $opportunity->fresh()->customFieldValues()
        ->where('custom_field_id', $this->stageField->getKey())
        ->value($this->stageField->getValueColumn());

    expect($updatedValue)->toBe($qualification->getKey());
});

it('edit card action updates the opportunity name', function (): void {
    $opportunity = Opportunity::factory()->recycle([$this->user, $this->team])->create(['name' => 'Old Name']);

    livewire(OpportunitiesBoard::class)
        ->callAction(
            TestAction::make('edit')->arguments(['recordKey' => $opportunity->getKey()]),
            data: ['name' => 'New Name'],
        )
        ->assertHasNoActionErrors();

    expect($opportunity->fresh()->name)->toBe('New Name');
});

it('edit card action preserves existing custom field values when name changes', function (): void {
    $amountField = CustomField::query()->forEntity(Opportunity::class)->where('code', OpportunityField::AMOUNT)->first();

    $opportunity = Opportunity::factory()->recycle([$this->user, $this->team])->create();
    $opportunity->saveCustomFieldValue($amountField, 50000);

    livewire(OpportunitiesBoard::class)
        ->callAction(
            TestAction::make('edit')->arguments(['recordKey' => $opportunity->getKey()]),
            data: ['name' => 'Updated Name'],
        )
        ->assertHasNoActionErrors();

    $value = $opportunity->fresh()->customFieldValues()
        ->where('custom_field_id', $amountField->getKey())
        ->value($amountField->getValueColumn());

    expect((float) $value)->toBe(50000.0);
});

it('delete card action soft-deletes the opportunity', function (): void {
    $opportunity = Opportunity::factory()->recycle([$this->user, $this->team])->create();

    livewire(OpportunitiesBoard::class)
        ->callAction(
            TestAction::make('delete')->arguments(['recordKey' => $opportunity->getKey()]),
        )
        ->assertHasNoActionErrors();

    $this->assertSoftDeleted($opportunity);
});

<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Note;
use App\Models\Opportunity;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;

/**
 * Seeds 8 curated portfolio companies with a healthy/at-risk mix for CRM Assistant demos.
 *
 * Healthy (recent activity): NovaTech Ventures, HealthData Corp, FinanceFlow AI, CloudSecure
 * At-risk (stale / no recent contact): GreenGrid Systems, RoboLogistics, BioSynth Labs, UrbanMobility Co
 */
final class DemoPortfolioSeeder extends Seeder
{
    public function run(?User $user = null, ?Team $team = null): void
    {
        if (! $user instanceof User || ! $team instanceof Team) {
            $user = User::factory()->withPersonalTeam()->create([
                'name' => 'Demo Portfolio Manager',
                'email' => 'demo@combinedperception.ai',
            ]);
            $team = $user->personalTeam();
        }

        $this->seedHealthyCompanies($user, $team);
        $this->seedAtRiskCompanies($user, $team);
    }

    private function seedHealthyCompanies(User $user, Team $team): void
    {
        $this->createCompanyWithActivity(
            user: $user,
            team: $team,
            name: 'NovaTech Ventures',
            notes: [
                ['title' => 'Quarterly business review — strong pipeline, expanding headcount to 85', 'daysAgo' => 3],
                ['title' => 'Intro call with new CTO — discussed platform migration roadmap', 'daysAgo' => 18],
            ],
            opportunities: [
                ['name' => 'Series B Portfolio Support', 'updatedDaysAgo' => 2],
                ['name' => 'Advisory Services Expansion', 'updatedDaysAgo' => 5],
            ],
        );

        $this->createCompanyWithActivity(
            user: $user,
            team: $team,
            name: 'HealthData Corp',
            notes: [
                ['title' => 'Product demo with VP Engineering — positive feedback on AI features', 'daysAgo' => 6],
                ['title' => 'Budget approval confirmed for Q3 expansion', 'daysAgo' => 21],
            ],
            opportunities: [
                ['name' => 'HIPAA Compliance Integration', 'updatedDaysAgo' => 4],
            ],
        );

        $this->createCompanyWithActivity(
            user: $user,
            team: $team,
            name: 'FinanceFlow AI',
            notes: [
                ['title' => 'Partnership call — finalising commercial terms for white-label deal', 'daysAgo' => 1],
                ['title' => 'Technical review of API integration — no blockers identified', 'daysAgo' => 14],
            ],
            opportunities: [
                ['name' => 'White-label Platform License', 'updatedDaysAgo' => 1],
                ['name' => 'Embedded Analytics Module', 'updatedDaysAgo' => 8],
            ],
        );

        $this->createCompanyWithActivity(
            user: $user,
            team: $team,
            name: 'CloudSecure',
            notes: [
                ['title' => 'SOC 2 audit prep meeting — timeline confirmed for Q3 completion', 'daysAgo' => 7],
            ],
            opportunities: [
                ['name' => 'Security Operations Retainer', 'updatedDaysAgo' => 6],
            ],
        );
    }

    private function seedAtRiskCompanies(User $user, Team $team): void
    {
        $this->createCompanyWithActivity(
            user: $user,
            team: $team,
            name: 'GreenGrid Systems',
            notes: [
                ['title' => 'Initial partnership scoping call — interested in grid monitoring solution', 'daysAgo' => 52],
                ['title' => 'Sent proposal — awaiting feedback from procurement team', 'daysAgo' => 61],
            ],
            opportunities: [
                ['name' => 'Grid Monitoring Platform', 'updatedDaysAgo' => 50],
            ],
            tasks: [
                ['title' => 'Follow up on Q2 proposal — no response received', 'createdDaysAgo' => 45],
            ],
        );

        $this->createCompanyWithActivity(
            user: $user,
            team: $team,
            name: 'RoboLogistics',
            notes: [
                ['title' => 'Demo call with ops team — positive reception, pending internal sign-off', 'daysAgo' => 78],
            ],
            opportunities: [
                ['name' => 'Warehouse Automation Integration', 'updatedDaysAgo' => 75],
                ['name' => 'Fleet Management Dashboard', 'updatedDaysAgo' => 80],
            ],
            tasks: [
                ['title' => 'Re-engage VP Operations — last contact 78 days ago', 'createdDaysAgo' => 60],
            ],
        );

        $this->createCompanyWithActivity(
            user: $user,
            team: $team,
            name: 'BioSynth Labs',
            notes: [],
            opportunities: [
                ['name' => 'Lab Data Management System', 'updatedDaysAgo' => 90],
            ],
            tasks: [
                ['title' => 'Schedule intro call with new Head of IT', 'createdDaysAgo' => 30],
            ],
        );

        $this->createCompanyWithActivity(
            user: $user,
            team: $team,
            name: 'UrbanMobility Co',
            notes: [
                ['title' => 'Met at MobilityWorld conference — strong interest in data platform', 'daysAgo' => 47],
            ],
            opportunities: [
                ['name' => 'Real-time Transit Analytics', 'updatedDaysAgo' => 44],
            ],
            tasks: [
                ['title' => 'Send case study and ROI calculator — promised at conference', 'createdDaysAgo' => 40],
            ],
        );
    }

    /**
     * @param  array<int, array{title: string, daysAgo: int}>  $notes
     * @param  array<int, array{name: string, updatedDaysAgo: int}>  $opportunities
     * @param  array<int, array{title: string, createdDaysAgo: int}>  $tasks
     */
    private function createCompanyWithActivity(
        User $user,
        Team $team,
        string $name,
        array $notes = [],
        array $opportunities = [],
        array $tasks = [],
    ): Company {
        $company = Company::factory()
            ->for($team, 'team')
            ->for($user, 'creator')
            ->create(['name' => $name]);

        foreach ($notes as $noteData) {
            $timestamp = Date::now()->subDays($noteData['daysAgo']);
            $note = Note::factory()
                ->for($team, 'team')
                ->create([
                    'title' => $noteData['title'],
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
            $note->companies()->sync([$company->id]);
        }

        foreach ($opportunities as $oppData) {
            $timestamp = Date::now()->subDays($oppData['updatedDaysAgo']);
            Opportunity::factory()
                ->for($team, 'team')
                ->create([
                    'name' => $oppData['name'],
                    'company_id' => $company->id,
                    'updated_at' => $timestamp,
                ]);
        }

        foreach ($tasks as $taskData) {
            $timestamp = Date::now()->subDays($taskData['createdDaysAgo']);
            $task = Task::factory()
                ->for($team, 'team')
                ->create([
                    'title' => $taskData['title'],
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
            $task->companies()->sync([$company->id]);
        }

        return $company;
    }
}

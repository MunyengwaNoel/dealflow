<?php

namespace App\Console\Commands;

use App\Models\Deal;
use App\Models\User;
use App\Notifications\DealFollowUpDue;
use Illuminate\Console\Command;

class BiztrackDealFollowUpReminder extends Command
{
    protected $signature = 'biztrack:deals:follow-up-reminders';

    protected $description = 'Notify assignees about deals with expected close dates soon or overdue';

    public function handle(): int
    {
        $n = 0;

        Deal::query()
            ->whereNotIn('stage', ['won', 'lost'])
            ->whereNotNull('expected_close_date')
            ->whereDate('expected_close_date', '<=', now()->addDays(2)->toDateString())
            ->whereNotNull('assigned_to')
            ->each(function (Deal $deal) use (&$n) {
                $user = User::query()->find($deal->assigned_to);
                if ($user) {
                    $user->notify(new DealFollowUpDue($deal));
                    $n++;
                }
            });

        $this->info('Sent '.$n.' deal follow-up notifications.');

        return self::SUCCESS;
    }
}

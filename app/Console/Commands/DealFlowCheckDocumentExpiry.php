<?php

namespace App\Console\Commands;

use App\Models\Document;
use App\Models\User;
use App\Notifications\DocumentExpiringSoon;
use Illuminate\Console\Command;

class DealFlowCheckDocumentExpiry extends Command
{
    protected $signature = 'dealflow:documents:check-expiry';

    protected $description = 'Notify tenant users about documents approaching expiry';

    public function handle(): int
    {
        $count = 0;

        Document::query()
            ->whereNotNull('expiry_date')
            ->chunkById(200, function ($documents) use (&$count) {
                foreach ($documents as $document) {
                    $days = now()->startOfDay()->diffInDays($document->expiry_date, false);
                    if ($days > (int) $document->reminder_days_before) {
                        continue;
                    }
                    if ($days < -60) {
                        continue;
                    }
                    if ($document->last_reminded_at && $document->last_reminded_at->isToday()) {
                        continue;
                    }

                    User::query()
                        ->where('tenant_id', $document->tenant_id)
                        ->each(function (User $user) use ($document) {
                            $user->notify(new DocumentExpiringSoon($document));
                        });

                    $document->update(['last_reminded_at' => now()]);
                    $count++;
                }
            });

        $this->info('Processed '.$count.' document reminders.');

        return self::SUCCESS;
    }
}

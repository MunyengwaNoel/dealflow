<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use Illuminate\Console\Command;

class DealFlowUpdateOverdueInvoices extends Command
{
    protected $signature = 'dealflow:invoices:mark-overdue';

    protected $description = 'Mark unpaid invoices as overdue when past due date';

    public function handle(): int
    {
        $updated = Invoice::query()
            ->whereIn('status', ['draft', 'sent', 'partial'])
            ->whereDate('due_date', '<', now()->toDateString())
            ->where('amount_due', '>', 0)
            ->update(['status' => 'overdue']);

        $this->info('Marked '.$updated.' invoices overdue.');

        return self::SUCCESS;
    }
}

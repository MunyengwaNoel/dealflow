<?php

namespace App\Console\Commands;

use App\Mail\MailpitPing;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class BiztrackMailpitTest extends Command
{
    protected $signature = 'biztrack:mailpit-test
                            {--sync : Send immediately instead of pushing to the queue}
                            {--to= : Recipient email (defaults to MAILPIT_TEST_TO or mailpit@localhost)}';

    protected $description = 'Queue (or send) a test message to verify Mailpit + queue workers';

    public function handle(): int
    {
        $to = $this->option('to') ?: config('mail.mailpit_test_to', 'mailpit@localhost');

        $mailable = new MailpitPing('Queued at '.now()->toIso8601String());

        if ($this->option('sync')) {
            Mail::to($to)->send($mailable);
            $this->info('Sent synchronously to '.$to.'. Check Mailpit UI (default http://127.0.0.1:8025).');
        } else {
            Mail::to($to)->queue($mailable);
            $this->info('Queued mail to '.$to.'. Run `php artisan queue:work` (or `composer dev`) and open Mailpit.');
        }

        return self::SUCCESS;
    }
}

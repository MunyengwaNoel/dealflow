<?php

namespace App\Notifications;

use App\Models\Deal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class DealFollowUpDue extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Deal $deal)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Deal follow-up',
            'message' => $this->deal->title.' expected close '.optional($this->deal->expected_close_date)->toDateString(),
            'related_type' => Deal::class,
            'related_id' => $this->deal->id,
        ];
    }
}

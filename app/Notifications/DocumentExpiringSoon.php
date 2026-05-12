<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class DocumentExpiringSoon extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Document $document)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Document expiring soon',
            'message' => $this->document->title.' expires on '.optional($this->document->expiry_date)->toDateString(),
            'related_type' => Document::class,
            'related_id' => $this->document->id,
        ];
    }
}

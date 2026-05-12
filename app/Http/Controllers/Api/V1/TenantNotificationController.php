<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

class TenantNotificationController extends ApiController
{
    public function index(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(25);

        return $this->successResponse($notifications->items(), 'OK', [
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
            ],
        ]);
    }

    public function markRead(Request $request, string $id)
    {
        $notification = $request->user()->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();

        return $this->successResponse((object) [], 'Marked read.');
    }
}

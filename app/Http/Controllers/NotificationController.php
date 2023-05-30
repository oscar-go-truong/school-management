<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function getTable()
    {
        return response()->json($this->notificationService->getTable());
    }

    public function show($id)
    {
        $notification = $this->notificationService->getById($id);
        if (!$notification) {
            return abort(404);
        } else {
            $this->notificationService->makeAsRead($id);
            return redirect($notification->url);
        }
    }
}

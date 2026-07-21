<?php

namespace App\Http\Controllers;

use App\Models\SmartNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationCenterController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = SmartNotification::query()
            ->where(function ($builder) use ($user) {
                $builder->where('user_id', $user->id);

                if ($user->company_id) {
                    $builder->orWhere(function ($companyQuery) use ($user) {
                        $companyQuery->whereNull('user_id')
                            ->where('company_id', $user->company_id);
                    });
                }
            });

        return response()->json([
            'unread_count' => (clone $query)->whereNull('read_at')->count(),
            'notifications' => $query->latest()->limit(12)->get()->map(fn (SmartNotification $notification) => [
                'id' => $notification->id,
                'title' => $notification->title,
                'message' => $notification->message,
                'type' => $notification->type,
                'icon' => $notification->icon ?: 'S',
                'url' => $notification->url ?: route('dashboard'),
                'read' => (bool) $notification->read_at,
                'created_at' => $notification->created_at?->diffForHumans(),
            ])->values(),
        ]);
    }

    public function read(Request $request, SmartNotification $notification): JsonResponse
    {
        abort_unless($notification->user_id === $request->user()->id || (
            $notification->user_id === null &&
            $notification->company_id !== null &&
            $notification->company_id === $request->user()->company_id
        ), 403);

        $notification->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }

    public function readAll(Request $request): JsonResponse
    {
        $user = $request->user();

        SmartNotification::query()
            ->whereNull('read_at')
            ->where(function ($builder) use ($user) {
                $builder->where('user_id', $user->id);

                if ($user->company_id) {
                    $builder->orWhere(function ($companyQuery) use ($user) {
                        $companyQuery->whereNull('user_id')
                            ->where('company_id', $user->company_id);
                    });
                }
            })
            ->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }
}

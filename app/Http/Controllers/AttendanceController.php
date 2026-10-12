<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /** View daily attendance records. */
    public function index()
    {
        $user = Auth::user();
        $attendances = $user->attendances()->orderByDesc('date')->paginate(20);
        $markedToday = $user->attendances()->whereDate('date', now()->toDateString())->exists();

        return view('attendance.index', compact('attendances', 'markedToday'));
    }

    /** Mark attendance once per day; cannot re-mark or delete afterwards. */
    public function store(WhatsAppService $whatsapp)
    {
        $user = Auth::user();
        $today = now()->toDateString();

        if ($user->attendances()->whereDate('date', $today)->exists()) {
            return back()->with('error', 'You have already marked your attendance for today.');
        }

        Attendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'status' => 'present',
            'marked_at' => now(),
        ]);

        $whatsapp->sendToUser($user, "Hi {$user->name}, your attendance for {$today} has been marked as PRESENT.");

        return back()->with('success', 'Attendance marked successfully for today.');
    }
}

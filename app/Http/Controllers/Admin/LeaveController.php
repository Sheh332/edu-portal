<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class LeaveController extends Controller
{
    /** All leave requests with per-student leave counts. */
    public function index()
    {
        $leaves = Leave::with('user')->orderByDesc('created_at')->paginate(20);

        // Approved-leave count per student for quick reference.
        $counts = Leave::selectRaw('user_id, count(*) as total')
            ->where('status', 'approved')
            ->groupBy('user_id')
            ->pluck('total', 'user_id');

        return view('admin.leaves.index', compact('leaves', 'counts'));
    }

    public function update(Request $request, Leave $leave, WhatsAppService $whatsapp)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['approved', 'rejected'])],
            'admin_comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $leave->update([
            'status' => $data['status'],
            'admin_comment' => $data['admin_comment'] ?? null,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        // On approval, mark each day in range as 'leave' in the attendance table.
        if ($data['status'] === 'approved') {
            $this->recordLeaveDays($leave);
        }

        try {
            $whatsapp->sendToUser(
                $leave->user,
                "Hi {$leave->user->name}, your leave request ({$leave->from_date->toDateString()} → {$leave->to_date->toDateString()}) has been {$data['status']}."
                .($data['admin_comment'] ? " Comment: {$data['admin_comment']}" : '')
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Leave notification error: '.$e->getMessage());
        }

        return redirect()->route('admin.leaves.index')->with('success', "Leave has been {$data['status']} successfully.");
    }

    protected function recordLeaveDays(Leave $leave): void
    {
        $period = Carbon::parse($leave->from_date)->daysUntil($leave->to_date);

        foreach ($period as $day) {
            Attendance::updateOrCreate(
                ['user_id' => $leave->user_id, 'date' => $day->toDateString()],
                ['status' => 'leave', 'marked_at' => now()]
            );
        }
    }
}

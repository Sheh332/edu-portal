<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    /** List the current user's leave requests with status. */
    public function index()
    {
        $leaves = Auth::user()->leaves()->orderByDesc('created_at')->paginate(15);

        return view('leaves.index', compact('leaves'));
    }

    public function create()
    {
        return view('leaves.create');
    }

    /** Send a leave request to the admin. */
    public function store(Request $request, WhatsAppService $whatsapp)
    {
        $data = $request->validate([
            'from_date' => ['required', 'date'],
            'to_date' => ['required', 'date', 'after_or_equal:from_date'],
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $user = Auth::user();

        $leave = $user->leaves()->create([
            'from_date' => $data['from_date'],
            'to_date' => $data['to_date'],
            'reason' => $data['reason'],
            'status' => 'pending',
        ]);

        // Notify every admin that a new leave request needs review.
        $message = "New leave request from {$user->name} ({$leave->from_date->toDateString()} → {$leave->to_date->toDateString()}). Reason: {$leave->reason}";
        foreach (User::role('admin')->get() as $admin) {
            $whatsapp->sendToUser($admin, $message);
        }
        // Confirm to the requesting user.
        $whatsapp->sendToUser($user, "Hi {$user->name}, your leave request has been submitted and is pending approval.");

        return redirect()->route('leaves.index')->with('success', 'Leave request submitted.');
    }
}

<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use Illuminate\Http\Request;

class StaffTicketsController extends Controller
{
    /**
     * Staff can view tickets (assigned to them or unassigned)
     */
    public function index(Request $request)
    {
        $query = SupportTicket::with(['user', 'assignedTo'])
            ->where(function($q) {
                $q->where('assigned_to', auth()->id())
                  ->orWhereNull('assigned_to');
            })
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->paginate(25);

        return view('staff.tickets.index', compact('tickets'));
    }

    /**
     * Staff can view ticket details if assigned or unassigned
     */
    public function show(SupportTicket $ticket)
    {
        // Ensure staff can only view their tickets or unassigned ones
        if ($ticket->assigned_to && $ticket->assigned_to !== auth()->id()) {
            abort(403, 'You do not have access to this ticket');
        }

        $ticket->load(['user', 'booking', 'assignedTo', 'replies.user']);
        return view('staff.tickets.show', compact('ticket'));
    }

    /**
     * Staff can reply to tickets
     */
    public function reply(Request $request, SupportTicket $ticket)
    {
        // Ensure staff can only reply to their tickets
        if ($ticket->assigned_to && $ticket->assigned_to !== auth()->id()) {
            abort(403, 'You do not have access to this ticket');
        }

        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        // Auto-assign if unassigned
        if (!$ticket->assigned_to) {
            $ticket->update([
                'assigned_to' => auth()->id(),
                'status' => 'IN_PROGRESS',
            ]);
        }

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $validated['message'],
            'is_staff_reply' => true,
        ]);

        return back()->with('success', 'Reply added successfully');
    }

    /**
     * Staff can update ticket status (limited to IN_PROGRESS and RESOLVED)
     */
    public function updateStatus(Request $request, SupportTicket $ticket)
    {
        // Ensure staff can only update their tickets
        if ($ticket->assigned_to !== auth()->id()) {
            abort(403, 'You do not have access to this ticket');
        }

        $validated = $request->validate([
            'status' => 'required|in:IN_PROGRESS,RESOLVED',
        ]);

        $update = ['status' => $validated['status']];

        if ($validated['status'] === 'RESOLVED') {
            $update['resolved_at'] = now();
        }

        $ticket->update($update);

        return back()->with('success', 'Ticket status updated');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TicketsController extends Controller
{
    public function index(Request $request)
    {
        $query = SupportTicket::with(['user', 'assignedTo'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $tickets = $query->paginate(25);
        $staff = User::where('role', 'staff')->orWhere('role', 'admin')->get();

        return view('admin.tickets.index', compact('tickets', 'staff'));
    }

    public function show(SupportTicket $ticket)
    {
        $ticket->load(['user', 'booking', 'assignedTo', 'replies.user']);
        return view('admin.tickets.show', compact('ticket'));
    }

    public function assign(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $ticket->update([
            'assigned_to' => $validated['assigned_to'],
            'status' => 'IN_PROGRESS',
        ]);

        Log::info('Ticket assigned', [
            'ticket_id' => $ticket->id,
            'assigned_to' => User::find($validated['assigned_to'])->name,
            'assigned_by' => auth()->user()->name,
        ]);

        return back()->with('success', 'Ticket assigned successfully');
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $validated['message'],
            'is_staff_reply' => true,
        ]);

        return back()->with('success', 'Reply added successfully');
    }

    public function updateStatus(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:OPEN,IN_PROGRESS,RESOLVED,CLOSED',
        ]);

        $update = ['status' => $validated['status']];

        if ($validated['status'] === 'RESOLVED' && $ticket->status !== 'RESOLVED') {
            $update['resolved_at'] = now();
        }

        $ticket->update($update);

        Log::info('Ticket status updated', [
            'ticket_id' => $ticket->id,
            'new_status' => $validated['status'],
            'updated_by' => auth()->user()->name,
        ]);

        return back()->with('success', 'Ticket status updated');
    }

    public function escalate(SupportTicket $ticket)
    {
        $ticket->update([
            'escalated' => true,
            'escalated_at' => now(),
            'priority' => 'URGENT',
        ]);

        Log::warning('Ticket escalated', [
            'ticket_id' => $ticket->id,
            'escalated_by' => auth()->user()->name,
        ]);

        return back()->with('success', 'Ticket escalated successfully');
    }
}

@extends('layouts.velzon.app')

@section('title', 'Support Tickets')
@section('page-title', 'Support Tickets')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">Staff Portal</p>
                <h1>Support Tickets</h1>
                <p class="lede">Manage assigned customer support tickets</p>
            </div>
        </div>

        <!-- Stats -->
        <div class="metrics-grid">
            <div class="chip">
                <p class="metric">{{ $stats['assigned_to_me'] ?? 0 }}</p>
                <p class="label">Assigned to Me</p>
            </div>
            <div class="chip">
                <p class="metric">{{ $stats['unassigned'] ?? 0 }}</p>
                <p class="label">Unassigned</p>
            </div>
            <div class="chip">
                <p class="metric">{{ $stats['in_progress'] ?? 0 }}</p>
                <p class="label">In Progress</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <form method="GET" action="{{ route('staff.tickets.index') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
                <div>
                    <label class="label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="OPEN" {{ request('status') == 'OPEN' ? 'selected' : '' }}>Open</option>
                        <option value="IN_PROGRESS" {{ request('status') == 'IN_PROGRESS' ? 'selected' : '' }}>In Progress</option>
                        <option value="RESOLVED" {{ request('status') == 'RESOLVED' ? 'selected' : '' }}>Resolved</option>
                    </select>
                </div>
                <div>
                    <label class="label">Assignment</label>
                    <select name="assignment" class="form-control">
                        <option value="">All</option>
                        <option value="mine" {{ request('assignment') == 'mine' ? 'selected' : '' }}>Assigned to Me</option>
                        <option value="unassigned" {{ request('assignment') == 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                    </select>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="pill" style="background: var(--brand-primary, #652482); color: white; border: none; cursor: pointer;">Filter</button>
                    <a href="{{ route('staff.tickets.index') }}" class="pill light" style="text-decoration: none; display: inline-flex; align-items: center;">Reset</a>
                </div>
            </form>
        </div>

        <!-- Tickets Table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ticket #</th>
                            <th>Subject</th>
                            <th>User</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Assigned To</th>
                            <th>Created</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr style="{{ $ticket->assigned_to == auth()->id() ? 'background: #F3F9FF;' : '' }}">
                                <td><code>{{ $ticket->ticket_number }}</code></td>
                                <td><strong>{{ Str::limit($ticket->subject, 30) }}</strong></td>
                                <td>{{ $ticket->user->name ?? 'N/A' }}</td>
                                <td><span class="pill light">{{ $ticket->category }}</span></td>
                                <td>
                                    @if($ticket->priority == 'HIGH' || $ticket->priority == 'URGENT')
                                        <span class="pill" style="background: #FFEBEE; color: #C62828;">{{ $ticket->priority }}</span>
                                    @elseif($ticket->priority == 'MEDIUM')
                                        <span class="pill" style="background: #FFF3E0; color: #E65100;">MEDIUM</span>
                                    @else
                                        <span class="pill light">LOW</span>
                                    @endif
                                </td>
                                <td>
                                    @if($ticket->status == 'OPEN')
                                        <span class="pill" style="background: #FFF3E0; color: #E65100;">Open</span>
                                    @elseif($ticket->status == 'IN_PROGRESS')
                                        <span class="pill" style="background: #E3F2FD; color: #1565C0;">In Progress</span>
                                    @elseif($ticket->status == 'RESOLVED')
                                        <span class="pill" style="background: #E8F5E9; color: #2E7D32;">Resolved</span>
                                    @else
                                        <span class="pill light">Closed</span>
                                    @endif
                                </td>
                                <td>
                                    @if($ticket->assigned_to == auth()->id())
                                        <span style="color: var(--brand-primary, #652482); font-weight: 600;">You</span>
                                    @elseif($ticket->assignedTo)
                                        {{ $ticket->assignedTo->name }}
                                    @else
                                        <span class="text-muted">Unassigned</span>
                                    @endif
                                </td>
                                <td>{{ $ticket->created_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ route('staff.tickets.show', $ticket) }}" class="link">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-3">No tickets found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($tickets->hasPages())
                <div class="card-footer">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>

        <div style="background: #E3F2FD; padding: 1rem; margin-top: 1.5rem; border-radius: 8px; border-left: 4px solid #1565C0;">
            <p style="margin: 0; color: #1565C0; font-size: 0.875rem;"><strong>Note:</strong> You can only view and respond to tickets assigned to you or unassigned tickets. Contact an admin to escalate urgent issues.</p>
        </div>
    </div>
@endsection

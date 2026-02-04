@extends('layouts.velzon.app')

@section('title', 'Support Tickets')
@section('page-title', 'Support Tickets')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">Support Management</p>
                <h1>Support Tickets</h1>
                <p class="lede">Customer support ticket management</p>
            </div>
        </div>

        <!-- Stats -->
        <div class="metrics-grid">
            <div class="chip">
                <p class="metric">{{ $stats['open'] ?? 0 }}</p>
                <p class="label">Open</p>
            </div>
            <div class="chip">
                <p class="metric">{{ $stats['in_progress'] ?? 0 }}</p>
                <p class="label">In Progress</p>
            </div>
            <div class="chip">
                <p class="metric">{{ $stats['resolved'] ?? 0 }}</p>
                <p class="label">Resolved</p>
            </div>
            <div class="chip">
                <p class="metric">{{ $stats['urgent'] ?? 0 }}</p>
                <p class="label">Urgent</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <form method="GET" action="{{ route('admin.tickets.index') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
                <div>
                    <label class="label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="OPEN" {{ request('status') == 'OPEN' ? 'selected' : '' }}>Open</option>
                        <option value="IN_PROGRESS" {{ request('status') == 'IN_PROGRESS' ? 'selected' : '' }}>In Progress</option>
                        <option value="RESOLVED" {{ request('status') == 'RESOLVED' ? 'selected' : '' }}>Resolved</option>
                        <option value="CLOSED" {{ request('status') == 'CLOSED' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <div>
                    <label class="label">Priority</label>
                    <select name="priority" class="form-control">
                        <option value="">All Priorities</option>
                        <option value="LOW" {{ request('priority') == 'LOW' ? 'selected' : '' }}>Low</option>
                        <option value="MEDIUM" {{ request('priority') == 'MEDIUM' ? 'selected' : '' }}>Medium</option>
                        <option value="HIGH" {{ request('priority') == 'HIGH' ? 'selected' : '' }}>High</option>
                        <option value="URGENT" {{ request('priority') == 'URGENT' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>
                <div>
                    <label class="label">Category</label>
                    <select name="category" class="form-control">
                        <option value="">All Categories</option>
                        <option value="BOOKING" {{ request('category') == 'BOOKING' ? 'selected' : '' }}>Booking</option>
                        <option value="PAYMENT" {{ request('category') == 'PAYMENT' ? 'selected' : '' }}>Payment</option>
                        <option value="PROPERTY" {{ request('category') == 'PROPERTY' ? 'selected' : '' }}>Property</option>
                        <option value="TECHNICAL" {{ request('category') == 'TECHNICAL' ? 'selected' : '' }}>Technical</option>
                        <option value="OTHER" {{ request('category') == 'OTHER' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="pill" style="background: var(--brand-primary, #652482); color: white; border: none; cursor: pointer;">Filter</button>
                    <a href="{{ route('admin.tickets.index') }}" class="pill light" style="text-decoration: none; display: inline-flex; align-items: center;">Reset</a>
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
                            <tr>
                                <td><code>{{ $ticket->ticket_number }}</code></td>
                                <td><strong>{{ Str::limit($ticket->subject, 30) }}</strong></td>
                                <td>{{ $ticket->user->name ?? 'N/A' }}</td>
                                <td><span class="pill light">{{ $ticket->category }}</span></td>
                                <td>
                                    @if($ticket->priority == 'URGENT')
                                        <span class="pill" style="background: #FFEBEE; color: #C62828;">URGENT</span>
                                    @elseif($ticket->priority == 'HIGH')
                                        <span class="pill" style="background: #FFF3E0; color: #E65100;">HIGH</span>
                                    @elseif($ticket->priority == 'MEDIUM')
                                        <span class="pill" style="background: #E3F2FD; color: #1565C0;">MEDIUM</span>
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
                                <td>{{ $ticket->assignedTo->name ?? 'Unassigned' }}</td>
                                <td>{{ $ticket->created_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ route('admin.tickets.show', $ticket) }}" class="link">View</a>
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
    </div>
@endsection

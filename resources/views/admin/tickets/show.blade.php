@extends('layouts.velzon.app')

@php
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag();
@endphp

@section('title', 'Ticket Details')
@section('page-title', 'Ticket Details')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">Support Management</p>
                <h1>Ticket {{ $ticket->ticket_number }}</h1>
                <p class="lede">{{ $ticket->subject }}</p>
            </div>
        </div>

        <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 1.5rem;">
            <!-- Ticket Thread -->
            <div>
                <!-- Original Ticket -->
                <div class="card">
                    <div style="display: flex; align-items: start; gap: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #E0E0E0;">
                        <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--brand-primary, #652482); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.25rem; flex-shrink: 0;">
                            {{ substr($ticket->user->name ?? 'U', 0, 1) }}
                        </div>
                        <div style="flex: 1;">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                                <div>
                                    <p style="margin: 0; font-weight: 600; font-size: 1.125rem;">{{ $ticket->user->name ?? 'Unknown' }}</p>
                                    <p style="margin: 0; font-size: 0.875rem; color: #5a5661;">{{ $ticket->created_at->format('M d, Y H:i') }}</p>
                                </div>
                                <span class="pill light">Customer</span>
                            </div>
                            <p style="margin: 0; line-height: 1.6; white-space: pre-wrap;">{{ $ticket->message }}</p>
                        </div>
                    </div>

                    <!-- Replies -->
                    @forelse($ticket->replies ?? [] as $reply)
                        <div style="display: flex; align-items: start; gap: 1rem; padding: 1rem 0; border-bottom: 1px solid #E0E0E0;">
                            <div style="width: 48px; height: 48px; border-radius: 50%; background: {{ $reply->is_staff_reply ? '#1565C0' : '#E0E0E0' }}; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.125rem; flex-shrink: 0;">
                                {{ substr($reply->user->name ?? 'U', 0, 1) }}
                            </div>
                            <div style="flex: 1;">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                                    <div>
                                        <p style="margin: 0; font-weight: 600;">{{ $reply->user->name ?? 'Unknown' }}</p>
                                        <p style="margin: 0; font-size: 0.875rem; color: #5a5661;">{{ $reply->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                    <span class="pill" style="background: {{ $reply->is_staff_reply ? '#E3F2FD' : '#F5F5F5' }}; color: {{ $reply->is_staff_reply ? '#1565C0' : '#5a5661' }};">
                                        {{ $reply->is_staff_reply ? 'Staff' : 'Customer' }}
                                    </span>
                                </div>
                                <p style="margin: 0; line-height: 1.6; white-space: pre-wrap;">{{ $reply->message }}</p>
                            </div>
                        </div>
                    @empty
                        <div style="padding: 2rem; text-align: center; color: #5a5661;">
                            <p style="margin: 0;">No replies yet</p>
                        </div>
                    @endforelse
                </div>

                <!-- Reply Form -->
                @if($ticket->status != 'CLOSED')
                <div class="card" style="margin-top: 1.5rem;">
                    <div class="card-head">
                        <h3>Add Reply</h3>
                    </div>
                    <form action="{{ route('admin.tickets.reply', $ticket) }}" method="POST">
                        @csrf
                        <div style="margin-bottom: 1.5rem;">
                            <textarea name="message" class="form-control" rows="6" placeholder="Type your response..." required></textarea>
                            @error('message')
                                <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="pill" style="background: var(--brand-primary, #652482); color: white; border: none; padding: 0.75rem 2rem; cursor: pointer;">Send Reply</button>
                    </form>
                </div>
                @endif
            </div>

            <!-- Ticket Details Sidebar -->
            <div>
                <div class="card">
                    <div class="card-head">
                        <h3>Ticket Details</h3>
                    </div>
                    <div style="display: grid; gap: 1rem;">
                        <div>
                            <p class="label" style="margin: 0 0 0.5rem;">Status</p>
                            @if($ticket->status == 'OPEN')
                                <span class="pill" style="background: #FFF3E0; color: #E65100; padding: 0.75rem 1rem;">Open</span>
                            @elseif($ticket->status == 'IN_PROGRESS')
                                <span class="pill" style="background: #E3F2FD; color: #1565C0; padding: 0.75rem 1rem;">In Progress</span>
                            @elseif($ticket->status == 'RESOLVED')
                                <span class="pill" style="background: #E8F5E9; color: #2E7D32; padding: 0.75rem 1rem;">Resolved</span>
                            @else
                                <span class="pill light" style="padding: 0.75rem 1rem;">Closed</span>
                            @endif
                        </div>
                        <div>
                            <p class="label" style="margin: 0 0 0.5rem;">Priority</p>
                            @if($ticket->priority == 'URGENT')
                                <span class="pill" style="background: #FFEBEE; color: #C62828; padding: 0.75rem 1rem;">URGENT</span>
                            @elseif($ticket->priority == 'HIGH')
                                <span class="pill" style="background: #FFF3E0; color: #E65100; padding: 0.75rem 1rem;">HIGH</span>
                            @elseif($ticket->priority == 'MEDIUM')
                                <span class="pill" style="background: #E3F2FD; color: #1565C0; padding: 0.75rem 1rem;">MEDIUM</span>
                            @else
                                <span class="pill light" style="padding: 0.75rem 1rem;">LOW</span>
                            @endif
                        </div>
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem;">Category</p>
                            <span class="pill light">{{ $ticket->category }}</span>
                        </div>
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem;">Customer</p>
                            <p style="margin: 0; font-weight: 600;">{{ $ticket->user->name ?? 'N/A' }}</p>
                            <p style="margin: 0.25rem 0 0; font-size: 0.875rem; color: #5a5661;">{{ $ticket->user->email ?? '' }}</p>
                        </div>
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem;">Assigned To</p>
                            @if($ticket->assignedTo)
                                <p style="margin: 0; font-weight: 600;">{{ $ticket->assignedTo->name }}</p>
                            @else
                                <p style="margin: 0; color: #C62828;">Unassigned</p>
                            @endif
                        </div>
                        @if($ticket->booking_id)
                            <div>
                                <p class="label" style="margin: 0 0 0.25rem;">Related Booking</p>
                                <a href="{{ route('admin.booking-detail', $ticket->booking_id) }}" class="link">#{{ $ticket->booking_id }}</a>
                            </div>
                        @endif
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem;">Created</p>
                            <p style="margin: 0;">{{ $ticket->created_at->format('M d, Y H:i') }}</p>
                            <p style="margin: 0.25rem 0 0; font-size: 0.875rem; color: #5a5661;">{{ $ticket->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Admin Actions -->
                @if($ticket->status != 'CLOSED')
                <div class="card" style="margin-top: 1.5rem;">
                    <div class="card-head">
                        <h3>Admin Actions</h3>
                    </div>
                    <div style="display: grid; gap: 0.75rem;">
                        <!-- Assign Ticket -->
                        @if(!$ticket->assigned_to)
                        <form action="{{ route('admin.tickets.assign', $ticket) }}" method="POST">
                            @csrf
                            <select name="staff_id" class="form-control" style="margin-bottom: 0.5rem;" required>
                                <option value="">Assign to staff...</option>
                                @foreach($staffUsers ?? [] as $staff)
                                    <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="pill light" style="width: 100%; justify-content: center; border: none; cursor: pointer;">Assign Ticket</button>
                        </form>
                        @endif

                        <!-- Update Status -->
                        <form action="{{ route('admin.tickets.status', $ticket) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="form-control" style="margin-bottom: 0.5rem;" required>
                                <option value="">Update status...</option>
                                <option value="OPEN">Open</option>
                                <option value="IN_PROGRESS">In Progress</option>
                                <option value="RESOLVED">Resolved</option>
                                <option value="CLOSED">Closed</option>
                            </select>
                            <button type="submit" class="pill light" style="width: 100%; justify-content: center; border: none; cursor: pointer;">Update Status</button>
                        </form>

                        <!-- Escalate -->
                        @if($ticket->priority != 'URGENT')
                        <form action="{{ route('admin.tickets.escalate', $ticket) }}" method="POST">
                            @csrf
                            <button type="submit" class="pill" style="background: #C62828; color: white; border: none; width: 100%; justify-content: center; cursor: pointer;" onclick="return confirm('Escalate this ticket to URGENT priority?')">⚠️ Escalate to Urgent</button>
                        </form>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

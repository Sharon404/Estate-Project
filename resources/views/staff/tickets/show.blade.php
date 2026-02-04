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
                <p class="eyebrow">Staff Portal</p>
                <h1>Ticket {{ $ticket->ticket_number }}</h1>
                <p class="lede">{{ $ticket->subject }}</p>
            </div>
            @if(!$ticket->assigned_to)
                <form action="{{ route('staff.tickets.reply', $ticket) }}" method="POST" style="display: inline;">
                    @csrf
                    <input type="hidden" name="auto_assign" value="1">
                    <button type="submit" class="pill" style="background: var(--brand-primary, #652482); color: white; border: none; cursor: pointer;">Assign to Me</button>
                </form>
            @endif
        </div>

        <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 1.5rem;">
            <!-- Ticket Thread -->
            <div>
                <!-- Original Ticket -->
                <div class="card">
                    <div style="display: flex; align-items: start; gap: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #E0E0E0;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--brand-primary, #652482); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0;">
                            {{ substr($ticket->user->name ?? 'U', 0, 1) }}
                        </div>
                        <div style="flex: 1;">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                                <div>
                                    <p style="margin: 0; font-weight: 600;">{{ $ticket->user->name ?? 'Unknown' }}</p>
                                    <p style="margin: 0; font-size: 0.875rem; color: #5a5661;">{{ $ticket->created_at->format('M d, Y H:i') }}</p>
                                </div>
                                <span class="pill light">Customer</span>
                            </div>
                            <p style="margin: 0; line-height: 1.6;">{{ $ticket->message }}</p>
                        </div>
                    </div>

                    <!-- Replies -->
                    @forelse($ticket->replies ?? [] as $reply)
                        <div style="display: flex; align-items: start; gap: 1rem; padding: 1rem 0; border-bottom: 1px solid #E0E0E0;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: {{ $reply->is_staff_reply ? '#1565C0' : '#E0E0E0' }}; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0;">
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
                                <p style="margin: 0; line-height: 1.6;">{{ $reply->message }}</p>
                            </div>
                        </div>
                    @empty
                        <div style="padding: 1rem 0; text-align: center; color: #5a5661; font-size: 0.875rem;">
                            No replies yet. Be the first to respond!
                        </div>
                    @endforelse
                </div>

                <!-- Reply Form -->
                @if($ticket->status != 'CLOSED')
                <div class="card" style="margin-top: 1.5rem;">
                    <div class="card-head">
                        <h3>Add Reply</h3>
                    </div>
                    <form action="{{ route('staff.tickets.reply', $ticket) }}" method="POST">
                        @csrf
                        <div style="margin-bottom: 1.5rem;">
                            <textarea name="message" class="form-control" rows="5" placeholder="Type your response..." required></textarea>
                            @error('message')
                                <span style="color: #C62828; font-size: 0.875rem;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div style="display: flex; gap: 1rem; align-items: center;">
                            <button type="submit" class="pill" style="background: var(--brand-primary, #652482); color: white; border: none; padding: 0.75rem 2rem; cursor: pointer;">Send Reply</button>
                            @if($ticket->status == 'OPEN')
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                    <input type="checkbox" name="mark_in_progress" value="1" checked>
                                    <span style="font-size: 0.875rem;">Mark as In Progress</span>
                                </label>
                            @endif
                        </div>
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
                            <p class="label" style="margin: 0 0 0.25rem;">Status</p>
                            @if($ticket->status == 'OPEN')
                                <span class="pill" style="background: #FFF3E0; color: #E65100;">Open</span>
                            @elseif($ticket->status == 'IN_PROGRESS')
                                <span class="pill" style="background: #E3F2FD; color: #1565C0;">In Progress</span>
                            @elseif($ticket->status == 'RESOLVED')
                                <span class="pill" style="background: #E8F5E9; color: #2E7D32;">Resolved</span>
                            @else
                                <span class="pill light">Closed</span>
                            @endif
                        </div>
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem;">Priority</p>
                            @if($ticket->priority == 'URGENT')
                                <span class="pill" style="background: #FFEBEE; color: #C62828;">URGENT</span>
                            @elseif($ticket->priority == 'HIGH')
                                <span class="pill" style="background: #FFF3E0; color: #E65100;">HIGH</span>
                            @elseif($ticket->priority == 'MEDIUM')
                                <span class="pill" style="background: #E3F2FD; color: #1565C0;">MEDIUM</span>
                            @else
                                <span class="pill light">LOW</span>
                            @endif
                        </div>
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem;">Category</p>
                            <span class="pill light">{{ $ticket->category }}</span>
                        </div>
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem;">Assigned To</p>
                            @if($ticket->assigned_to == auth()->id())
                                <p style="margin: 0; color: var(--brand-primary, #652482); font-weight: 600;">You</p>
                            @elseif($ticket->assignedTo)
                                <p style="margin: 0;">{{ $ticket->assignedTo->name }}</p>
                            @else
                                <p style="margin: 0; color: #5a5661;">Unassigned</p>
                            @endif
                        </div>
                        @if($ticket->booking_id)
                            <div>
                                <p class="label" style="margin: 0 0 0.25rem;">Related Booking</p>
                                <a href="{{ route('staff.bookings.show', $ticket->booking_id) }}" class="link">#{{ $ticket->booking_id }}</a>
                            </div>
                        @endif
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem;">Created</p>
                            <p style="margin: 0; font-size: 0.875rem;">{{ $ticket->created_at->format('M d, Y H:i') }}</p>
                            <p style="margin: 0.25rem 0 0; font-size: 0.75rem; color: #5a5661;">{{ $ticket->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Status Actions -->
                @if($ticket->status != 'CLOSED' && $ticket->assigned_to == auth()->id())
                <div class="card" style="margin-top: 1.5rem;">
                    <div class="card-head">
                        <h3>Quick Actions</h3>
                    </div>
                    <form action="{{ route('staff.tickets.status', $ticket) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div style="display: grid; gap: 0.75rem;">
                            @if($ticket->status == 'OPEN')
                                <button type="submit" name="status" value="IN_PROGRESS" class="pill" style="background: #E3F2FD; color: #1565C0; border: none; cursor: pointer; justify-content: center;">Mark In Progress</button>
                            @endif
                            @if($ticket->status == 'IN_PROGRESS')
                                <button type="submit" name="status" value="RESOLVED" class="pill" style="background: #E8F5E9; color: #2E7D32; border: none; cursor: pointer; justify-content: center;">Mark Resolved</button>
                            @endif
                        </div>
                    </form>
                </div>
                @endif

                <div style="background: #E3F2FD; padding: 1rem; margin-top: 1.5rem; border-radius: 8px;">
                    <p style="margin: 0; color: #1565C0; font-size: 0.875rem;"><strong>Note:</strong> Only admins can close tickets. Mark as resolved when issue is fixed.</p>
                </div>
            </div>
        </div>
    </div>
@endsection

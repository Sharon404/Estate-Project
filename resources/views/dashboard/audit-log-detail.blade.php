@extends('layouts.velzon.app')

@section('title', 'Audit Log Details')
@section('page-title', 'Audit Log Entry')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">System Event</p>
                <h1>{{ $log->action }}</h1>
                <p class="lede">{{ $log->description }}</p>
            </div>
            <a href="{{ route('admin.audit-logs') }}" style="padding: 0.5rem 1rem; background: #2196F3; color: white; border: none; border-radius: 4px; text-decoration: none;">← Back</a>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
            <!-- Left Column: Details -->
            <div>
                <div class="card">
                    <div class="card-head">
                        <h3>Event Information</h3>
                    </div>
                    <div style="display: grid; gap: 1rem;">
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
                            <strong>Timestamp:</strong>
                            <span>{{ $log->created_at->format('M d, Y H:i:s') }}</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
                            <strong>Action:</strong>
                            <span>{{ $log->action }}</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
                            <strong>Resource Type:</strong>
                            <span>{{ $log->resource_type }}</span>
                        </div>
                        @if($log->resource_id)
                            <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
                                <strong>Resource ID:</strong>
                                <span style="font-family: monospace;">#{{ $log->resource_id }}</span>
                            </div>
                        @endif
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
                            <strong>Description:</strong>
                            <span>{{ $log->description ?? 'N/A' }}</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                            <strong>Status:</strong>
                            @if($log->status === 'success')
                                <span style="background: #C8E6C9; color: #2E7D32; padding: 0.25rem 0.75rem; border-radius: 4px; font-weight: bold; display: inline-block; width: fit-content;">Success</span>
                            @elseif($log->status === 'failed')
                                <span style="background: #FFCDD2; color: #C62828; padding: 0.25rem 0.75rem; border-radius: 4px; font-weight: bold; display: inline-block; width: fit-content;">Failed</span>
                            @else
                                <span style="background: #FFF9C4; color: #F57F17; padding: 0.25rem 0.75rem; border-radius: 4px; font-weight: bold; display: inline-block; width: fit-content;">{{ ucfirst($log->status) }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($log->error_message)
                    <div class="card" style="background: #FFEBEE; border-color: #FFCDD2;">
                        <div class="card-head">
                            <h3 style="color: #C62828;">Error Message</h3>
                        </div>
                        <p style="color: #C62828; font-family: monospace; margin: 0;">{{ $log->error_message }}</p>
                    </div>
                @endif

                @if($log->changes && is_array($log->changes) && count($log->changes) > 0)
                    <div class="card">
                        <div class="card-head">
                            <h3>Changes Made</h3>
                        </div>
                        <div style="display: grid; gap: 1rem;">
                            @foreach($log->changes as $field => $change)
                                <div style="border-bottom: 1px solid #eee; padding-bottom: 1rem;">
                                    <strong style="display: block; margin-bottom: 0.5rem;">{{ $field }}</strong>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                        <div style="background: #FFEBEE; padding: 0.75rem; border-radius: 4px;">
                                            <small style="color: #999; display: block; margin-bottom: 0.25rem;">From:</small>
                                            <code style="font-family: monospace; font-size: 0.875rem;">
                                                @if(is_array($change['old'] ?? null))
                                                    {{ json_encode($change['old'], JSON_PRETTY_PRINT) }}
                                                @else
                                                    {{ $change['old'] ?? 'null' }}
                                                @endif
                                            </code>
                                        </div>
                                        <div style="background: #E8F5E9; padding: 0.75rem; border-radius: 4px;">
                                            <small style="color: #999; display: block; margin-bottom: 0.25rem;">To:</small>
                                            <code style="font-family: monospace; font-size: 0.875rem;">
                                                @if(is_array($change['new'] ?? null))
                                                    {{ json_encode($change['new'], JSON_PRETTY_PRINT) }}
                                                @else
                                                    {{ $change['new'] ?? 'null' }}
                                                @endif
                                            </code>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($log->metadata && is_array($log->metadata) && count($log->metadata) > 0)
                    <div class="card">
                        <div class="card-head">
                            <h3>Additional Metadata</h3>
                        </div>
                        <pre style="background: #f5f5f5; padding: 1rem; border-radius: 4px; overflow-x: auto; margin: 0;">{{ json_encode($log->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                    </div>
                @endif
            </div>

            <!-- Right Column: User & System Info -->
            <div>
                <div class="card">
                    <div class="card-head">
                        <h3>User Information</h3>
                    </div>
                    @if($log->user)
                        <div style="display: grid; gap: 1rem;">
                            <div>
                                <small style="color: #999;">Name</small>
                                <p style="margin: 0.5rem 0 0 0; font-weight: bold;">{{ $log->user->name }}</p>
                            </div>
                            <div>
                                <small style="color: #999;">Email</small>
                                <p style="margin: 0.5rem 0 0 0;">{{ $log->user->email }}</p>
                            </div>
                            <div>
                                <small style="color: #999;">Role</small>
                                <p style="margin: 0.5rem 0 0 0; display: inline-block; background: #E3F2FD; color: #1565C0; padding: 0.25rem 0.75rem; border-radius: 4px; font-weight: bold;">{{ ucfirst($log->user_role) }}</p>
                            </div>
                        </div>
                    @else
                        <p style="color: #999;">System Event (No user associated)</p>
                    @endif
                </div>

                <div class="card">
                    <div class="card-head">
                        <h3>Technical Details</h3>
                    </div>
                    <div style="display: grid; gap: 1rem; font-size: 0.875rem;">
                        <div>
                            <small style="color: #999;">IP Address</small>
                            <p style="margin: 0.5rem 0 0 0; font-family: monospace;">{{ $log->ip_address ?? 'N/A' }}</p>
                        </div>
                        @if($log->user_agent)
                            <div>
                                <small style="color: #999;">User Agent</small>
                                <p style="margin: 0.5rem 0 0 0; font-family: monospace; word-break: break-all; max-height: 100px; overflow-y: auto;">{{ $log->user_agent }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .dash-shell { max-width: 1200px; margin: 0 auto; }
        .dash-head { display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem; }
        .dash-head h1 { font-size: 2rem; margin: 0.5rem 0; }
        .card { border: 1px solid #e0e0e0; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem; }
        .card-head { margin-bottom: 1rem; }
        .card-head h3 { margin: 0; font-size: 1rem; }
    </style>
@endsection

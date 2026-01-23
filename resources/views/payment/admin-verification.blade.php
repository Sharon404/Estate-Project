@extends('layouts.app')

@section('title', 'Manual Payment Verification - Admin')

@section('content')
<div class="min-h-screen bg-gray-100 py-12 px-4">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Manual Payment Verification</h1>
            <p class="text-gray-600">Verify and approve guest M-PESA payments</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm font-semibold">Pending Verification</p>
                <p class="text-3xl font-bold text-blue-600" id="pending_count">0</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm font-semibold">Verified Today</p>
                <p class="text-3xl font-bold text-green-600" id="verified_count">0</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm font-semibold">Rejected Today</p>
                <p class="text-3xl font-bold text-red-600" id="rejected_count">0</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm font-semibold">Total Amount</p>
                <p class="text-3xl font-bold text-indigo-600" id="total_amount">KES 0</p>
            </div>
        </div>

        <!-- Pending Submissions Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                <h2 class="text-white text-xl font-bold flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Pending Submissions
                </h2>
            </div>

            <div id="submissions_container" class="divide-y">
                <div class="p-6 text-center text-gray-500">
                    <p>Loading submissions...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Verification Modal -->
<div id="verify_modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="bg-green-50 border-b px-6 py-4">
            <h3 class="text-lg font-bold text-gray-900">Verify Payment</h3>
        </div>
        <form id="verify_form" onsubmit="submitVerification(event)">
            <div class="p-6">
                <input type="hidden" id="verify_submission_id">
                
                <div class="mb-4">
                    <p class="text-gray-700 mb-2">
                        <strong>Booking:</strong> <span id="verify_booking"></span>
                    </p>
                    <p class="text-gray-700 mb-2">
                        <strong>Guest:</strong> <span id="verify_guest"></span>
                    </p>
                    <p class="text-gray-700 mb-2">
                        <strong>Receipt:</strong> <span id="verify_receipt" class="font-mono text-blue-600"></span>
                    </p>
                    <p class="text-gray-700 mb-4">
                        <strong>Amount:</strong> <span id="verify_amount" class="text-lg text-green-600 font-bold"></span>
                    </p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Verification Notes (optional)</label>
                    <textarea 
                        id="verify_notes" 
                        placeholder="e.g., Verified against M-PESA statement..."
                        class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-green-500"
                        rows="3"
                    ></textarea>
                </div>

                <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-gray-700">
                        ✓ Verify that you've checked the M-PESA statement
                        <br/>✓ Confirm the amount matches
                        <br/>✓ Ensure the receipt number is valid
                    </p>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex gap-3">
                <button type="button" onclick="closeVerifyModal()" class="flex-1 px-4 py-2 border-2 border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-100">
                    Cancel
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700">
                    Verify Payment
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Rejection Modal -->
<div id="reject_modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="bg-red-50 border-b px-6 py-4">
            <h3 class="text-lg font-bold text-gray-900">Reject Payment</h3>
        </div>
        <form id="reject_form" onsubmit="submitRejection(event)">
            <div class="p-6">
                <input type="hidden" id="reject_submission_id">
                
                <div class="mb-4">
                    <p class="text-gray-700 mb-2">
                        <strong>Receipt:</strong> <span id="reject_receipt" class="font-mono text-blue-600"></span>
                    </p>
                    <p class="text-gray-700 mb-4">
                        <strong>Amount:</strong> <span id="reject_amount" class="text-lg text-gray-900 font-bold"></span>
                    </p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Rejection Reason *</label>
                    <select id="reject_reason" required class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-red-500">
                        <option value="">-- Select a reason --</option>
                        <option value="Receipt not found in M-PESA records">Receipt not found in M-PESA records</option>
                        <option value="Amount does not match">Amount does not match</option>
                        <option value="Receipt already used">Receipt already used</option>
                        <option value="Possible fraudulent transaction">Possible fraudulent transaction</option>
                        <option value="Payment appears incomplete">Payment appears incomplete</option>
                        <option value="Other">Other reason</option>
                    </select>
                </div>

                <div id="other_reason_container" class="hidden mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Please explain</label>
                    <textarea 
                        id="reject_other_notes" 
                        placeholder="Enter your rejection reason..."
                        class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-red-500"
                        rows="3"
                    ></textarea>
                </div>

                <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm text-gray-700">
                        ⚠ The guest will be notified of the rejection and asked to resubmit.
                    </p>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex gap-3">
                <button type="button" onclick="closeRejectModal()" class="flex-1 px-4 py-2 border-2 border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-100">
                    Cancel
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700">
                    Reject Payment
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Detail Modal -->
<div id="detail_modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="bg-blue-50 border-b px-6 py-4 sticky top-0">
            <h3 class="text-lg font-bold text-gray-900">Submission Details</h3>
        </div>
        <div id="detail_content" class="p-6">
            <!-- Content loaded dynamically -->
        </div>
        <div class="bg-gray-50 px-6 py-4 border-t sticky bottom-0">
            <button onclick="closeDetailModal()" class="px-4 py-2 border-2 border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-100">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    let allSubmissions = [];

    // Load pending submissions
    async function loadPendingSubmissions() {
        try {
            const response = await fetch('/admin/payment/manual-submissions/pending', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });

            const data = await response.json();

            if (data.success) {
                allSubmissions = data.data || [];
                renderSubmissions();
                updateStats();
            }
        } catch (error) {
            console.error('Error loading submissions:', error);
            document.getElementById('submissions_container').innerHTML = `
                <div class="p-6 text-center text-red-500">
                    <p>Error loading submissions</p>
                </div>
            `;
        }
    }

    // Render submissions table
    function renderSubmissions() {
        const container = document.getElementById('submissions_container');

        if (allSubmissions.length === 0) {
            container.innerHTML = `
                <div class="p-12 text-center text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-lg font-semibold">No pending submissions</p>
                    <p>All payments have been verified!</p>
                </div>
            `;
            return;
        }

        container.innerHTML = allSubmissions.map(submission => `
            <div class="p-6 hover:bg-gray-50 transition">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-gray-500 text-sm">Booking Reference</p>
                        <p class="text-lg font-semibold text-gray-900">${submission.booking_ref}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Guest Name</p>
                        <p class="text-lg font-semibold text-gray-900">${submission.guest_name}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">M-PESA Receipt</p>
                        <p class="text-lg font-mono font-bold text-blue-600">${submission.receipt_number}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Amount</p>
                        <p class="text-lg font-semibold text-green-600">${formatCurrency(submission.amount)}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Submitted</p>
                        <p class="text-lg font-semibold text-gray-900">${formatDate(submission.submitted_at)}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Email</p>
                        <p class="text-lg font-semibold text-gray-900">${submission.guest_email}</p>
                    </div>
                </div>

                @if(auth()->check() && auth()->user()->isAdmin)
                <div class="flex gap-2 pt-4 border-t">
                    <button 
                        onclick="showDetailModal(${submission.id})" 
                        class="px-4 py-2 text-gray-700 font-semibold border-2 border-gray-300 rounded-lg hover:bg-gray-100"
                    >
                        View Details
                    </button>
                    <button 
                        onclick="openVerifyModal(${submission.id}, '${submission.booking_ref}', '${submission.guest_name}', '${submission.receipt_number}', ${submission.amount})" 
                        class="px-4 py-2 text-white font-semibold bg-green-600 rounded-lg hover:bg-green-700 flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Verify
                    </button>
                    <button 
                        onclick="openRejectModal(${submission.id}, '${submission.receipt_number}', ${submission.amount})" 
                        class="px-4 py-2 text-white font-semibold bg-red-600 rounded-lg hover:bg-red-700 flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        Reject
                    </button>
                </div>
                @endif
            </div>
        `).join('');
    }

    // Modal functions
    function openVerifyModal(id, booking, guest, receipt, amount) {
        document.getElementById('verify_submission_id').value = id;
        document.getElementById('verify_booking').textContent = booking;
        document.getElementById('verify_guest').textContent = guest;
        document.getElementById('verify_receipt').textContent = receipt;
        document.getElementById('verify_amount').textContent = formatCurrency(amount);
        document.getElementById('verify_notes').value = '';
        document.getElementById('verify_modal').classList.remove('hidden');
    }

    function closeVerifyModal() {
        document.getElementById('verify_modal').classList.add('hidden');
    }

    function openRejectModal(id, receipt, amount) {
        document.getElementById('reject_submission_id').value = id;
        document.getElementById('reject_receipt').textContent = receipt;
        document.getElementById('reject_amount').textContent = formatCurrency(amount);
        document.getElementById('reject_reason').value = '';
        document.getElementById('reject_other_notes').value = '';
        document.getElementById('reject_modal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('reject_modal').classList.add('hidden');
    }

    async function showDetailModal(submissionId) {
        try {
            const response = await fetch(`/admin/payment/manual-submissions/${submissionId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });

            const data = await response.json();
            const submission = data.data;

            document.getElementById('detail_content').innerHTML = `
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-500 text-sm">Booking Reference</p>
                            <p class="text-lg font-semibold">${submission.booking_ref}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Status</p>
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold ${getStatusBadge(submission.status)}">
                                ${submission.status}
                            </span>
                        </div>
                    </div>

                    <div class="border-t pt-6">
                        <h4 class="font-semibold text-gray-900 mb-4">Guest Information</h4>
                        <div class="space-y-2">
                            <p><strong>Name:</strong> ${submission.guest_name}</p>
                            <p><strong>Email:</strong> ${submission.guest_email}</p>
                            <p><strong>Phone:</strong> ${submission.phone_e164 || 'Not provided'}</p>
                        </div>
                    </div>

                    <div class="border-t pt-6">
                        <h4 class="font-semibold text-gray-900 mb-4">Payment Details</h4>
                        <div class="space-y-2">
                            <p><strong>Receipt Number:</strong> <code class="text-blue-600 font-mono">${submission.receipt_number}</code></p>
                            <p><strong>Amount:</strong> <span class="text-lg text-green-600 font-bold">${formatCurrency(submission.amount)}</span></p>
                            <p><strong>Submitted:</strong> ${formatDateTime(submission.submitted_at)}</p>
                            <p><strong>Guest Notes:</strong> ${submission.raw_notes || 'None'}</p>
                        </div>
                    </div>

                    ${submission.review_notes ? `
                    <div class="border-t pt-6 bg-blue-50 p-4 rounded">
                        <h4 class="font-semibold text-gray-900 mb-2">Admin Notes</h4>
                        <p>${submission.review_notes}</p>
                        <p class="text-sm text-gray-600 mt-2">By: ${submission.reviewed_by}</p>
                    </div>
                    ` : ''}

                    <div class="border-t pt-6">
                        <h4 class="font-semibold text-gray-900 mb-4">Property Information</h4>
                        <div class="space-y-2">
                            <p><strong>Property:</strong> ${submission.property_name}</p>
                            <p><strong>Check-in:</strong> ${formatDate(submission.check_in)}</p>
                            <p><strong>Check-out:</strong> ${formatDate(submission.check_out)}</p>
                        </div>
                    </div>
                </div>
            `;
            document.getElementById('detail_modal').classList.remove('hidden');
        } catch (error) {
            console.error('Error loading details:', error);
            alert('Error loading submission details');
        }
    }

    function closeDetailModal() {
        document.getElementById('detail_modal').classList.add('hidden');
    }

    // Submit handlers
    async function submitVerification(event) {
        event.preventDefault();

        const submissionId = document.getElementById('verify_submission_id').value;
        const notes = document.getElementById('verify_notes').value;

        try {
            const response = await fetch(`/admin/payment/manual-submissions/${submissionId}/verify`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    verified_notes: notes || ''
                })
            });

            const data = await response.json();

            if (data.success) {
                closeVerifyModal();
                alert('Payment verified successfully!');
                loadPendingSubmissions();
            } else {
                alert(data.message || 'Error verifying payment');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while verifying payment');
        }
    }

    async function submitRejection(event) {
        event.preventDefault();

        const submissionId = document.getElementById('reject_submission_id').value;
        const reason = document.getElementById('reject_reason').value;
        const otherNotes = document.getElementById('reject_other_notes').value;

        const rejectionReason = reason === 'Other' ? otherNotes : reason;

        try {
            const response = await fetch(`/admin/payment/manual-submissions/${submissionId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    rejection_reason: rejectionReason
                })
            });

            const data = await response.json();

            if (data.success) {
                closeRejectModal();
                alert('Payment rejected. Guest has been notified.');
                loadPendingSubmissions();
            } else {
                alert(data.message || 'Error rejecting payment');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while rejecting payment');
        }
    }

    // Utility functions
    function formatCurrency(amount) {
        return new Intl.NumberFormat('en-KE', {
            style: 'currency',
            currency: 'KES'
        }).format(amount);
    }

    function formatDate(dateString) {
        if (!dateString) return '-';
        return new Date(dateString).toLocaleDateString('en-KE', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }

    function formatDateTime(dateString) {
        if (!dateString) return '-';
        return new Date(dateString).toLocaleDateString('en-KE', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function getStatusBadge(status) {
        const badges = {
            'SUBMITTED': 'bg-yellow-100 text-yellow-800',
            'VERIFIED': 'bg-green-100 text-green-800',
            'REJECTED': 'bg-red-100 text-red-800',
        };
        return badges[status] || 'bg-gray-100 text-gray-800';
    }

    function updateStats() {
        const pendingCount = allSubmissions.filter(s => s.status === 'SUBMITTED').length;
        const totalAmount = allSubmissions.reduce((sum, s) => sum + parseFloat(s.amount), 0);

        document.getElementById('pending_count').textContent = pendingCount;
        document.getElementById('total_amount').textContent = formatCurrency(totalAmount);
    }

    // Handle reason selection
    document.addEventListener('change', function(e) {
        if (e.target.id === 'reject_reason') {
            const otherContainer = document.getElementById('other_reason_container');
            if (e.target.value === 'Other') {
                otherContainer.classList.remove('hidden');
                document.getElementById('reject_other_notes').required = true;
            } else {
                otherContainer.classList.add('hidden');
                document.getElementById('reject_other_notes').required = false;
            }
        }
    });

    // Load on page load
    document.addEventListener('DOMContentLoaded', loadPendingSubmissions);

    // Refresh every 30 seconds
    setInterval(loadPendingSubmissions, 30000);
</script>
@endsection

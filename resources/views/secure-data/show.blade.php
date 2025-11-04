@extends('layouts.app')

@section('title', 'View Secure Record')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-eye"></i> Secure Record Details
                    </h4>
                    <div>
                        <span class="badge encryption-badge">
                            <i class="fas fa-lock"></i> Encrypted Data
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <strong>Record ID:</strong> #{{ $record->id }}<br>
                        <strong>Security Level:</strong>
                        <span class="badge bg-{{
                            $record->security_level == 'low' ? 'success' :
                            ($record->security_level == 'medium' ? 'warning' :
                            ($record->security_level == 'high' ? 'orange' : 'danger'))
                        }}">
                            {{ ucfirst($record->security_level) }}
                        </span><br>
                        <strong>Status:</strong>
                        <span class="badge {{ $record->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $record->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <strong>Created:</strong> {{ $record->created_at->format('M d, Y H:i:s') }}<br>
                        <strong>Updated:</strong> {{ $record->updated_at->format('M d, Y H:i:s') }}<br>
                        <strong>Storage:</strong> <span class="text-success">AES-256 Encrypted</span>
                    </div>
                </div>

                <hr>

                <h5 class="mb-3"><i class="fas fa-user"></i> Personal Information</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label"><strong>Full Name</strong></label>
                            <div class="form-control bg-light">{{ $record->name }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label"><strong>Email Address</strong></label>
                            <div class="form-control bg-light">{{ $record->email }}</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label"><strong>Phone Number</strong></label>
                            <div class="form-control bg-light">{{ $record->phone }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label"><strong>Social Security Number</strong></label>
                            <div class="form-control bg-light">{{ $record->social_security_number }}</div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label"><strong>Full Address</strong></label>
                    <div class="form-control bg-light" style="min-height: 60px;">{{ $record->address }}</div>
                </div>

                <hr>

                <h5 class="mb-3"><i class="fas fa-credit-card"></i> Financial Information</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label"><strong>Credit Card Number</strong></label>
                            <div class="form-control bg-light">{{ $record->credit_card }}</div>
                        </div>
                    </div>
                </div>

                @if($record->financial_info)
                <div class="mb-3">
                    <label class="form-label"><strong>Additional Financial Info</strong></label>
                    <div class="form-control bg-light" style="min-height: 80px;">{{ $record->financial_info }}</div>
                </div>
                @endif

                <hr>

                <h5 class="mb-3"><i class="fas fa-heartbeat"></i> Medical Information</h5>
                @if($record->medical_info)
                    <div class="form-control bg-light" style="min-height: 100px;">{{ $record->medical_info }}</div>
                @else
                    <div class="alert alert-info">No medical information provided.</div>
                @endif

                <hr>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('secure-data.edit', $record->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Record
                    </a>
                    <a href="{{ route('secure-data.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                    <button type="button" class="btn btn-info" onclick="verifyEncryption({{ $record->id }})">
                        <i class="fas fa-shield-alt"></i> Verify Encryption
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function verifyEncryption(id) {
    fetch(`/secure-data/verify/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ Encryption verification successful!\n\nData is properly encrypted and decrypted.');
            } else {
                alert('❌ Encryption verification failed!\n\n' + data.error);
            }
        })
        .catch(error => {
            alert('❌ Error verifying encryption: ' + error);
        });
}
</script>
@endpush

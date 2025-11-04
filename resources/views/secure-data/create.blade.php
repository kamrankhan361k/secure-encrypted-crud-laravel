@extends('layouts.app')

@section('title', 'Create Secure Record')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-plus-circle"></i> Create New Secure Record
                </h4>
                <small class="text-muted">All data will be encrypted using multi-layer AES-256 encryption</small>
            </div>
            <div class="card-body">
                <form action="{{ route('secure-data.store') }}" method="POST" id="secureForm">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="name" name="name"
                                       value="{{ old('name') }}" required maxlength="255">
                                @error('name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email"
                                       value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                       value="{{ old('phone') }}" required maxlength="20">
                                @error('phone')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="credit_card" class="form-label">Credit Card *</label>
                                <input type="text" class="form-control" id="credit_card" name="credit_card"
                                       value="{{ old('credit_card') }}" required maxlength="19"
                                       placeholder="XXXX-XXXX-XXXX-XXXX">
                                @error('credit_card')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="social_security_number" class="form-label">Social Security Number *</label>
                                <input type="text" class="form-control" id="social_security_number"
                                       name="social_security_number" value="{{ old('social_security_number') }}"
                                       pattern="\d{3}-\d{2}-\d{4}" placeholder="XXX-XX-XXXX" required>
                                @error('social_security_number')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="security_level" class="form-label">Security Level *</label>
                                <select class="form-select" id="security_level" name="security_level" required>
                                    <option value="">Select Security Level</option>
                                    @foreach($securityLevels as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('security_level') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('security_level')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Full Address *</label>
                        <textarea class="form-control" id="address" name="address" rows="3"
                                  required maxlength="500">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="medical_info" class="form-label">Medical Information</label>
                                <textarea class="form-control" id="medical_info" name="medical_info"
                                          rows="4" maxlength="1000">{{ old('medical_info') }}</textarea>
                                @error('medical_info')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="financial_info" class="form-label">Financial Information</label>
                                <textarea class="form-control" id="financial_info" name="financial_info"
                                          rows="4" maxlength="1000">{{ old('financial_info') }}</textarea>
                                @error('financial_info')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 form-check form-switch">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active Record</label>
                    </div>

                    <div class="alert alert-warning">
                        <h5><i class="fas fa-shield-alt"></i> Security Information</h5>
                        <ul class="mb-0">
                            <li>All data is encrypted using multi-layer AES-256 encryption</li>
                            <li>Encryption includes random salting and integrity checks</li>
                            <li>Data is obfuscated with character substitution</li>
                            <li>Each field uses unique encryption parameters</li>
                        </ul>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-lock"></i> Create Secure Record
                        </button>
                        <a href="{{ route('secure-data.index') }}" class="btn btn-secondary btn-lg">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('secureForm').addEventListener('submit', function(e) {
    const ssn = document.getElementById('social_security_number');
    const ssnValue = ssn.value.replace(/-/g, '');

    if (ssnValue.length !== 9 || !/^\d+$/.test(ssnValue)) {
        e.preventDefault();
        alert('SSN must be exactly 9 digits (format: XXX-XX-XXXX)');
        ssn.focus();
        return;
    }

    const creditCard = document.getElementById('credit_card');
    const ccValue = creditCard.value.replace(/-/g, '');

    if (ccValue.length < 15 || ccValue.length > 16 || !/^\d+$/.test(ccValue)) {
        e.preventDefault();
        alert('Credit card must be 15-16 digits');
        creditCard.focus();
        return;
    }
});
</script>
@endpush

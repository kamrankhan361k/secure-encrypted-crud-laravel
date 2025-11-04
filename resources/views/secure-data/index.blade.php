@extends('layouts.app')

@section('title', 'Secure Encrypted Records')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-database"></i> Encrypted Records
                    </h4>
                    <span class="badge encryption-badge">
                        <i class="fas fa-lock"></i> AES-256 Encrypted
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>All sensitive data is protected with multi-layer AES-256 encryption</strong> -
                    Names, emails, phone numbers, and financial data are encrypted at rest.
                </div>

                @if($records->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Security Level</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($records as $record)
                                <tr class="security-{{ $record->security_level }}">
                                    <td><strong>#{{ $record->id }}</strong></td>
                                    <td>{{ $record->name }}</td>
                                    <td>{{ $record->email }}</td>
                                    <td>{{ $record->phone }}</td>
                                    <td>
                                        <span class="badge bg-{{
                                            $record->security_level == 'low' ? 'success' :
                                            ($record->security_level == 'medium' ? 'warning' :
                                            ($record->security_level == 'high' ? 'orange' : 'danger'))
                                        }}">
                                            {{ ucfirst($record->security_level) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $record->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $record->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ $record->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('secure-data.show', $record->id) }}"
                                               class="btn btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('secure-data.edit', $record->id) }}"
                                               class="btn btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('secure-data.toggle-status', $record->id) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-{{ $record->is_active ? 'warning' : 'success' }}"
                                                        title="{{ $record->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="fas fa-{{ $record->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('secure-data.destroy', $record->id) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"
                                                        onclick="return confirm('Permanently delete this record?')"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <span class="text-muted">
                                Showing {{ $records->firstItem() }} to {{ $records->lastItem() }}
                                of {{ $records->total() }} records
                            </span>
                        </div>
                        <div>
                            {{ $records->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-database fa-3x text-muted mb-3"></i>
                        <h4>No encrypted records found</h4>
                        <p class="text-muted">Create your first secure record to get started.</p>
                        <a href="{{ route('secure-data.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle"></i> Create First Record
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Roles & Permissions')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold tracking-tight text-dark mb-1">Roles &amp; Access Controls</h2>
    <p class="text-muted mb-0 small">Define security roles, customize granular permissions, and assign roles to users.</p>
</div>

<div class="row g-4">
    <!-- Roles Matrix -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-dark mb-0">Configured System Roles</h6>
                <button class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#addRole">
                    <i class="bi bi-shield-plus"></i> Create Role
                </button>
            </div>
            <div class="card-body p-4">
                @foreach($roles as $role)
                    <div class="card border border-light-subtle shadow-none mb-3 p-3" style="background:#f8fafc; border-radius:0.85rem;">
                        <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                            @csrf @method('PUT')
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary px-3 py-2 fw-bold text-capitalize fs-6">{{ $role->name }}</span>
                                    @if($role->name === 'admin')
                                        <span class="badge bg-danger">Super User (All Access)</span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    @if($role->name !== 'admin')
                                        <button class="btn btn-sm btn-success px-3 shadow-xs">Update Permissions</button>
                                    @endif
                                    @if(!in_array($role->name, ['admin','student']))
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="document.getElementById('del-{{ $role->id }}').submit()" title="Delete Role">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row g-2">
                                @foreach($permissions as $perm)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="permissions[]" value="{{ $perm->name }}"
                                                   id="p-{{ $role->id }}-{{ $perm->id }}"
                                                   @checked($role->hasPermissionTo($perm->name)) @disabled($role->name==='admin')>
                                            <label class="form-check-label small text-secondary" for="p-{{ $role->id }}-{{ $perm->id }}">
                                                {{ $perm->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </form>

                        @if(!in_array($role->name, ['admin','student']))
                            <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" id="del-{{ $role->id }}" onsubmit="return confirm('Are you sure you want to delete this role?')">
                                @csrf @method('DELETE')
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- User Assignment & List -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3 px-4">
                <h6 class="fw-bold text-dark mb-0">Assign Role to User</h6>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.roles.assign') }}" class="mb-4">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Target User</label>
                        <select name="user_id" class="form-select" required>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->getRoleNames()->implode(', ') ?: 'No role' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">Assign Role</label>
                        <select name="role" class="form-select" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2 shadow-sm">
                        <i class="bi bi-shield-check"></i> Save Role Assignment
                    </button>
                </form>

                <h6 class="fw-bold text-dark mb-3">User Roles Directory</h6>
                <div class="list-group list-group-flush border rounded-3 overflow-hidden">
                    @foreach($users as $u)
                        <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                            <div>
                                <span class="fw-semibold text-dark small">{{ $u->name }}</span>
                                <div class="text-muted" style="font-size:0.75rem;">{{ $u->email }}</div>
                            </div>
                            <div>
                                @foreach($u->roles as $r)
                                    <span class="badge bg-{{ $r->name === 'admin' ? 'danger' : 'secondary' }}">{{ $r->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Add Role -->
<div class="modal fade" id="addRole" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.roles.store') }}" class="modal-content border-0 shadow-lg" style="border-radius:1rem;">
            @csrf
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Create Custom Role</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary small">Role Name</label>
                    <input name="name" class="form-control" placeholder="e.g. instructor, supervisor" required>
                </div>
                <label class="form-label fw-semibold text-secondary small">Initial Permissions</label>
                <div class="row g-2">
                    @foreach($permissions as $perm)
                        <div class="col-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="permissions[]" value="{{ $perm->name }}" id="np-{{ $perm->id }}">
                                <label class="form-check-label small text-secondary" for="np-{{ $perm->id }}">{{ $perm->name }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary px-4">Create Role</button>
            </div>
        </form>
    </div>
</div>
@endsection

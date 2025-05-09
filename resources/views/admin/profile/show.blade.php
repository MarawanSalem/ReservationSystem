@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <img src="{{ $user->image ? Storage::url($user->image) : '' }}"
                         alt="{{ $user->name }}"
                         data-name="{{ $user->name }}"
                         class="user-avatar-auto rounded-circle mb-3"
                         style="width: 150px; height: 150px;">
                    <h4 class="mb-0">{{ $user->name }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    <div class="mb-3">
                        <span class="badge bg-primary rounded-pill">Administrator</span>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i> Edit Profile
                        </a>
                        <a href="{{ route('admin.profile.password') }}" class="btn btn-secondary">
                            <i class="fas fa-key me-1"></i> Change Password
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Profile Information</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th style="width: 30%;">Username</th>
                                    <td>{{ $user->username }}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>{{ $user->phone }}</td>
                                </tr>
                                <tr>
                                    <th>Bio</th>
                                    <td>{{ $user->bio ?? 'No bio provided' }}</td>
                                </tr>
                                <tr>
                                    <th>Member Since</th>
                                    <td>{{ $user->created_at->format('F j, Y') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

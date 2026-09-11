@extends('layouts.admin')

@section('title', 'Trainers')

@section('content')
<div class="dashboard-wrap">

    <header class="dashboard-header">
        <h1>Trainers</h1>
    </header>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <table class="admin-table">
        <thead>
            <tr>
                <th>Order</th>
                <th>Photo</th>
                <th>Name</th>
                <th>Role</th>
                <th>Bio</th>
            </tr>
        </thead>
        <tbody>
            @forelse($trainers as $trainer)
                <tr>
                    <td>{{ $trainer->display_order }}</td>
                    <td>
                        @if($trainer->photo)
                            <img src="{{ asset('storage/' . $trainer->photo) }}" alt="{{ $trainer->name }}" class="trainer-thumb">
                        @else
                            <span class="trainer-thumb-placeholder">{{ strtoupper(substr($trainer->name, 0, 1)) }}</span>
                        @endif
                    </td>
                    <td>{{ $trainer->name }}</td>
                    <td>{{ $trainer->role }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($trainer->bio, 60) }}</td>
                </tr>
            @empty
                <tr><td colspan="5">No trainers yet.</td></tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection
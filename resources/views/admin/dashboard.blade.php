@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-wrap">

   <header class="dashboard-header">
    <h1>ChampionClub Admin</h1>
     <div style="display:flex; gap:12px; align-items:center;">
        <a href="{{ route('admin.plans.index') }}" class="btn-logout">Manage Plans</a>
        <a href="{{ route('admin.trainers.index') }}" class="btn-logout">Manage Trainers</a>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
     </div>
   </header>

    <div class="stat-cards">
        <div class="stat-card">
            <span class="stat-card-label">Total Enquiries</span>
            <span class="stat-card-value">{{ $totalQueries }}</span>
        </div>
    </div>

    <div class="dashboard-section">
        <h2>All Enquiries</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Interest</th>
                    <th>Message</th>
                    <th>Received</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enquiries as $enquiry)
                    <tr>
                        <td>{{ $enquiry->name }}</td>
                        <td>{{ $enquiry->email }}</td>
                        <td>{{ $enquiry->phone ?? '—' }}</td>
                        <td>{{ $enquiry->interest ?? '—' }}</td>
                        <td>{{ $enquiry->message }}</td>
                        <td>{{ $enquiry->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6">No enquiries yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
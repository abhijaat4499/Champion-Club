@extends('layouts.admin')

@section('title', 'Membership Plans')

@section('content')
<div class="dashboard-wrap">

    <header class="dashboard-header">
        <h1>Membership Plans</h1>
         <a href="{{ route('admin.plans.create') }}" class="btn-admin-small">+ Add Plan</a>
    </header>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Order</th>
                <th>Name</th>
                <th>Price</th>
                <th>Featured</th>
                <th>Color</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($plans as $plan)
                <tr>
                    <td>{{ $plan->display_order }}</td>
                    <td>{{ $plan->name }}</td>
                    <td>₹{{ number_format($plan->price) }}{{ $plan->period }}</td>
                    <td>{{ $plan->is_featured ? 'Yes' : '—' }}</td>
                    <td>{{ ucfirst($plan->color) }}</td>
                     <td> <a href="{{ route('admin.plans.edit', $plan) }}">Edit</a>
            &nbsp;|&nbsp;
            <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('Delete this plan?');" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-link-delete">Delete</button>
            </form>
        </td>
                </tr>
            @empty
                <tr><td colspan="6">No plans yet.</td></tr>
            @endforelse
        </tbody>
    </table>

</div>
@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif
@endsection
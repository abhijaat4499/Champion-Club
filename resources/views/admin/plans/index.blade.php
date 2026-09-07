@extends('layouts.admin')

@section('title', 'Membership Plans')

@section('content')
<div class="dashboard-wrap">

    <header class="dashboard-header">
        <h1>Membership Plans</h1>
    </header>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Order</th>
                <th>Name</th>
                <th>Price</th>
                <th>Featured</th>
                <th>Color</th>
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
                </tr>
            @empty
                <tr><td colspan="5">No plans yet.</td></tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection
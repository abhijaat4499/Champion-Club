@extends('layouts.admin-panel')

@section('title', 'Membership Plans')

@section('content')
<div class="dashboard-wrap">

    <x-admin.page-header title="Membership Plans" icon="membership">
        <a href="{{ route('admin.plans.create') }}" class="btn-admin-small">+ Add Plan</a>
    </x-admin.page-header>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <table class="admin-table">
        <thead>
            <tr>
                <th class="col-order">Order</th>
                <th>Plan</th>
                <th>Price</th>
                <th>Badge color</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($plans as $plan)
                <tr>
                    <td class="col-order"><span class="order-chip">{{ $plan->display_order }}</span></td>
                    <td>
                        <span class="person-name">{{ $plan->name }}</span>
                        @if($plan->is_featured)
                            <span class="badge badge-featured">Most popular</span>
                        @endif
                    </td>
                    <td><span class="price">₹{{ number_format($plan->price) }}</span><span class="person-sub-inline">{{ $plan->period }}</span></td>
                    <td><span class="color-chip color-{{ $plan->color }}">{{ ucfirst($plan->color) }}</span></td>
                    <td class="text-right">
                        <div class="row-actions">
                            <a href="{{ route('admin.plans.edit', $plan) }}" class="btn-edit">Edit</a>

                            <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST"
                                  onsubmit="return confirm('Delete this plan?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="table-empty">No plans yet.</td></tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection

@extends('layouts.admin-panel')

@section('title', 'Offers')

@section('content')
<div class="dashboard-wrap">

    <x-admin.page-header title="Event Offers" icon="offers">
        <a href="{{ route('gym.offers') }}" class="btn-logout" target="_blank" rel="noopener">View on website</a>
        <a href="{{ route('admin.offers.create') }}" class="btn-admin-small">+ Add Offer</a>
    </x-admin.page-header>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <table class="admin-table">
        <thead>
            <tr>
                <th class="col-order">Order</th>
                <th>Offer</th>
                <th>Price</th>
                <th>Dates</th>
                <th>Status</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($offers as $offer)
                @php
                    $status = ! $offer->is_active ? 'hidden' : ($offer->isExpired() ? 'expired' : ($offer->isScheduled() ? 'scheduled' : 'live'));
                @endphp
                <tr>
                    <td class="col-order"><span class="order-chip">{{ $offer->display_order }}</span></td>
                    <td>
                        <span class="person-name">{{ $offer->title }}</span>
                        <span class="event-badge event-{{ $offer->event }}">{{ $offer->eventLabel() }}{{ $offer->event === 'new_year' ? ' ' . $offer->newYearNumber() : '' }}</span>
                    </td>
                    <td class="nowrap">
                        <span class="price">₹{{ number_format($offer->price) }}</span>
                        @if($offer->discountPercent())
                            <span class="person-sub"><s>₹{{ number_format($offer->original_price) }}</s> · {{ $offer->discountPercent() }}% off</span>
                        @endif
                    </td>
                    <td class="nowrap">
                        @if($offer->starts_on)
                            {{ $offer->starts_on->format('d M') }} – {{ $offer->valid_until->format('d M Y') }}
                        @else
                            Till {{ $offer->valid_until->format('d M Y') }}
                        @endif
                    </td>
                    <td>
                        <span class="status-chip status-{{ $status }}">
                            {{ ['live' => 'Live', 'scheduled' => 'Scheduled', 'expired' => 'Expired', 'hidden' => 'Hidden'][$status] }}
                        </span>
                    </td>
                    <td class="text-right">
                        <div class="row-actions">
                            <a href="{{ route('admin.offers.edit', $offer) }}" class="btn-edit">Edit</a>

                            <form action="{{ route('admin.offers.destroy', $offer) }}" method="POST"
                                  onsubmit="return confirm('Delete this offer?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="table-empty">
                        No offers yet.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection

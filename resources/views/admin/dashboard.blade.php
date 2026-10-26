@extends('layouts.admin-panel')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-wrap">

    <x-admin.page-header title="Enquiries" icon="dashboard" />

    <div class="dashboard-section">

        {{-- Filters --}}
        <form method="GET" action="{{ route('admin.dashboard') }}" class="filter-bar">
            <div class="filter-field">
                <label for="from">From</label>
                <input type="date" id="from" name="from" value="{{ $from }}">
            </div>

            <div class="filter-field">
                <label for="to">To</label>
                <input type="date" id="to" name="to" value="{{ $to }}">
            </div>

            <button type="submit" class="btn-admin-small">Apply filter</button>

            @if($from || $to || $sort !== 'newest')
                <a href="{{ route('admin.dashboard') }}" class="btn-logout">Reset</a>
            @endif

            <div class="filter-field filter-sort">
                <label for="sort">Sort by</label>
                <select id="sort" name="sort" onchange="this.form.submit()">
                    @foreach($sortOptions as $value => $label)
                        <option value="{{ $value }}" {{ $sort === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        @if($errors->any())
            <div class="alert-error">{{ $errors->first() }}</div>
        @endif

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Lead</th>
                    <th>Phone</th>
                    <th>Interest</th>
                    <th>Message</th>
                    <th class="text-right">Received</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enquiries as $enquiry)
                    <tr>
                        <td>
                            <span class="person-name">{{ $enquiry->name }}</span>
                            <span class="person-sub">{{ $enquiry->email }}</span>
                        </td>
                        <td class="nowrap">
                            @if($call = $enquiry->callableNumber())
                                <a href="tel:{{ $call }}" class="phone-call" title="Call {{ $enquiry->name }}" aria-label="Call {{ $enquiry->name }} on {{ $enquiry->phone }}">
                                    <span class="phone-call-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24"><path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.25 11.4 11.4 0 0 0 3.6.57 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.45.57 3.57a1 1 0 0 1-.25 1z"/></svg>
                                    </span>
                                    {{ $enquiry->phone }}
                                </a>
                            @else
                                {{ $enquiry->phone ?? '—' }}
                            @endif
                        </td>
                        <td>
                            @if($enquiry->interest)
                                <span class="badge {{ $enquiry->interest === 'offer' ? 'badge-featured' : '' }}">{{ $enquiry->interestLabel() }}</span>
                            @endif
                            @if($enquiry->subject)
                                <span class="lead-subject">{{ $enquiry->subject }}</span>
                            @elseif(! $enquiry->interest)
                                —
                            @endif
                        </td>
                        <td class="col-bio">{{ $enquiry->message }}</td>
                        <td class="text-right nowrap">
                            {{ $enquiry->created_at->timezone($timezone)->format('d M Y, h:i A') }}
                            <span class="cell-sub">{{ $enquiry->created_at->diffForHumans() }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="table-empty">
                            {{ ($from || $to) ? 'No enquiries found for this date range.' : 'No enquiries yet.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($enquiries->total() > 0)
            <div class="table-footer">
                <span class="table-count">
                    Showing {{ $enquiries->firstItem() }}–{{ $enquiries->lastItem() }} of {{ $enquiries->total() }} enquiries
                </span>
                {{ $enquiries->links('pagination::default') }}
            </div>
        @endif
    </div>

</div>
@endsection

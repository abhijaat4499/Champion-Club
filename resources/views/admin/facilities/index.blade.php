@extends('layouts.admin-panel')

@section('title', 'Facilities')

@section('content')
<div class="dashboard-wrap">

    <x-admin.page-header title="Facilities" icon="facilities">
        <a href="{{ route('gym.facilities') }}" class="btn-logout" target="_blank" rel="noopener">View on website</a>
        <a href="{{ route('admin.facilities.create') }}" class="btn-admin-small">+ Add Facility</a>
    </x-admin.page-header>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <p class="settings-note">Shown on the Facilities page. The first five also appear on the home page.</p>

    <table class="admin-table">
        <thead>
            <tr>
                <th class="col-order">Order</th>
                <th>Facility</th>
                <th>Description</th>
                <th>Status</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($facilities as $facility)
                <tr>
                    <td class="col-order"><span class="order-chip">{{ $facility->display_order }}</span></td>
                    <td>
                        <div class="person-cell">
                            @if($facility->image)
                                <img src="{{ $facility->imageUrl() }}" alt="{{ $facility->title }}" class="service-thumb" style="{{ $facility->imageFocus() }}">
                            @else
                                <span class="service-thumb service-thumb-empty">{{ mb_strtoupper(mb_substr($facility->title, 0, 1)) }}</span>
                            @endif
                            <span class="person-name">{{ $facility->title }}</span>
                        </div>
                    </td>
                    <td class="col-bio">{{ \Illuminate\Support\Str::limit($facility->text, 90) }}</td>
                    <td>
                        <span class="status-chip status-{{ $facility->is_active ? 'live' : 'hidden' }}">{{ $facility->is_active ? 'Live' : 'Hidden' }}</span>
                    </td>
                    <td class="text-right">
                        <div class="row-actions">
                            <a href="{{ route('admin.facilities.edit', $facility) }}" class="btn-edit">Edit</a>

                            <form action="{{ route('admin.facilities.destroy', $facility) }}" method="POST"
                                  onsubmit="return confirm('Delete this facility?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="table-empty">No facilities yet. Add your first facility to show it on the website.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection

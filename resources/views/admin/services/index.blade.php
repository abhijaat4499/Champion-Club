@extends('layouts.admin-panel')

@section('title', 'Services')

@section('content')
<div class="dashboard-wrap">

    <x-admin.page-header title="Services" icon="services">
        <a href="{{ route('gym.services') }}" class="btn-logout" target="_blank" rel="noopener">View on website</a>
        <a href="{{ route('admin.services.create') }}" class="btn-admin-small">+ Add Service</a>
    </x-admin.page-header>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <table class="admin-table">
        <thead>
            <tr>
                <th class="col-order">Order</th>
                <th>Service</th>
                <th>Summary</th>
                <th>Status</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($services as $service)
                <tr>
                    <td class="col-order"><span class="order-chip">{{ $service->display_order }}</span></td>
                    <td>
                        <div class="person-cell">
                            @if($service->image)
                                <img src="{{ $service->imageUrl() }}" alt="{{ $service->title }}" class="service-thumb">
                            @else
                                <span class="service-thumb service-thumb-empty">{{ mb_strtoupper(mb_substr($service->title, 0, 1)) }}</span>
                            @endif
                            <div>
                                <span class="person-name">{{ $service->title }}</span>
                                <span class="person-sub">/services/{{ $service->slug }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="col-bio">{{ \Illuminate\Support\Str::limit($service->summary, 90) }}</td>
                    <td>
                        <span class="status-chip status-{{ $service->is_active ? 'live' : 'hidden' }}">{{ $service->is_active ? 'Live' : 'Hidden' }}</span>
                    </td>
                    <td class="text-right">
                        <div class="row-actions">
                            @if($service->is_active)
                                <a href="{{ route('gym.service', $service) }}" class="btn-edit" target="_blank" rel="noopener">View</a>
                            @endif
                            <a href="{{ route('admin.services.edit', $service) }}" class="btn-edit">Edit</a>

                            <form action="{{ route('admin.services.destroy', $service) }}" method="POST"
                                  onsubmit="return confirm('Delete this service?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="table-empty">No services yet. Add your first service to show it on the website.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection

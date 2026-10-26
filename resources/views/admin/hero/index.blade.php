@extends('layouts.admin-panel')

@section('title', 'Homepage')

@section('content')
<div class="dashboard-wrap">

    <x-admin.page-header title="Homepage" icon="homepage">
        <a href="{{ url('/') }}" class="btn-logout" target="_blank" rel="noopener">View on website</a>
    </x-admin.page-header>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="admin-subhead">
        <div>
            <h2>Hero slides</h2>
            <p class="settings-note">The big sliding banner at the top of the home page. Each slide has its own photo, text, points and buttons.</p>
        </div>
        <a href="{{ route('admin.hero.create') }}" class="btn-admin-small">+ Add Slide</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th class="col-order">Order</th>
                <th>Slide</th>
                <th>Pattern</th>
                <th>Points</th>
                <th>Status</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($slides as $slide)
                <tr>
                    <td class="col-order"><span class="order-chip">{{ $slide->display_order }}</span></td>
                    <td>
                        <div class="person-cell">
                            @if($slide->image)
                                <img src="{{ asset('storage/' . ($slide->image_mobile ?: $slide->image)) }}" alt="" class="service-thumb" style="{{ $slide->focus() }}">
                            @elseif($slide->site_image)
                                <img src="{{ asset(config('site-images.' . $slide->site_image . '.sm')) }}" alt="" class="service-thumb">
                            @endif
                            <div>
                                <span class="person-name">{{ \Illuminate\Support\Str::limit($slide->title, 60) }}</span>
                                <span class="person-sub">Tab: {{ $slide->tab }}</span>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge">{{ \App\Models\HeroSlide::PATTERNS[$slide->patternName()]['label'] }}</span></td>
                    <td class="nowrap">{{ \App\Models\HeroSlide::POINT_STYLES[$slide->points_style] ?? '—' }}</td>
                    <td>
                        <span class="status-chip status-{{ $slide->is_active ? 'live' : 'hidden' }}">{{ $slide->is_active ? 'Live' : 'Hidden' }}</span>
                    </td>
                    <td class="text-right">
                        <div class="row-actions">
                            <a href="{{ route('admin.hero.edit', $slide) }}" class="btn-edit">Edit</a>
                            <form action="{{ route('admin.hero.destroy', $slide) }}" method="POST" onsubmit="return confirm('Delete this slide?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="table-empty">No slides yet — the home page shows the default slides until you add one.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection

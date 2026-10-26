@extends('layouts.admin-panel')

@section('title', 'Testimonials')

@section('content')
<div class="dashboard-wrap">

    <x-admin.page-header title="Testimonials" icon="testimonials">
        <a href="{{ url('/') }}#video-reviews" class="btn-logout" target="_blank" rel="noopener">View on website</a>
    </x-admin.page-header>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    {{-- ---------- Reviews awaiting approval ---------- --}}
    @if($pending->isNotEmpty())
        <div class="admin-subhead">
            <div>
                <h2>New reviews from the website <span class="count-pill">{{ $pending->count() }}</span></h2>
                <p class="settings-note">Visitors sent these from the home page. Approve a review to show it on the website, or delete it.</p>
            </div>
        </div>

        <table class="admin-table pending-table">
            <thead>
                <tr>
                    <th>Member</th>
                    <th>Review</th>
                    <th>Rating</th>
                    <th>Received</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pending as $review)
                    <tr>
                        <td>
                            <div class="person-cell">
                                <span class="trainer-thumb-placeholder">{{ $review->initials() }}</span>
                                <span class="person-name">{{ $review->name }}</span>
                            </div>
                        </td>
                        <td class="col-bio">{{ $review->quote }}</td>
                        <td class="nowrap"><span class="rating-chip">{{ $review->rating }} / 5</span></td>
                        <td class="nowrap">{{ $review->created_at->diffForHumans() }}</td>
                        <td class="text-right">
                            <div class="row-actions">
                                <form action="{{ route('admin.testimonials.approve', $review) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-approve">Approve</button>
                                </form>
                                <a href="{{ route('admin.testimonials.edit', $review) }}" class="btn-edit">Edit</a>
                                <form action="{{ route('admin.testimonials.destroy', $review) }}" method="POST" onsubmit="return confirm('Delete this review?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- ---------- Client reviews ---------- --}}
    <div class="admin-subhead{{ $pending->isNotEmpty() ? ' admin-subhead-spaced' : '' }}">
        <div>
            <h2>Client reviews</h2>
            <p class="settings-note">Shown in the "What our members say" carousel on the home page.</p>
        </div>
        <a href="{{ route('admin.testimonials.create') }}" class="btn-admin-small">+ Add Review</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th class="col-order">Order</th>
                <th>Member</th>
                <th>Review</th>
                <th>Rating</th>
                <th>Status</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $review)
                <tr>
                    <td class="col-order"><span class="order-chip">{{ $review->display_order }}</span></td>
                    <td>
                        <div class="person-cell">
                            <span class="trainer-thumb-placeholder">{{ $review->initials() }}</span>
                            <div>
                                <span class="person-name">{{ $review->name }}</span>
                                @if($review->from_website)
                                    <span class="person-sub">Sent from website</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="col-bio">{{ \Illuminate\Support\Str::limit($review->quote, 90) }}</td>
                    <td class="nowrap"><span class="rating-chip">{{ $review->rating }} / 5</span></td>
                    <td>
                        <span class="status-chip status-{{ $review->is_active ? 'live' : 'hidden' }}">{{ $review->is_active ? 'Live' : 'Hidden' }}</span>
                    </td>
                    <td class="text-right">
                        <div class="row-actions">
                            <a href="{{ route('admin.testimonials.edit', $review) }}" class="btn-edit">Edit</a>
                            <form action="{{ route('admin.testimonials.destroy', $review) }}" method="POST" onsubmit="return confirm('Delete this review?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="table-empty">No reviews yet. Add one to show it on the home page.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ---------- Video reviews ---------- --}}
    <div class="admin-subhead admin-subhead-spaced" id="videos">
        <div>
            <h2>Video reviews</h2>
            <p class="settings-note">YouTube videos shown in the "Video reviews" carousel on the home page.</p>
        </div>
        <a href="{{ route('admin.videos.create') }}" class="btn-admin-small">+ Add Video</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th class="col-order">Order</th>
                <th>Video</th>
                <th>Member</th>
                <th>Status</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($videos as $video)
                <tr>
                    <td class="col-order"><span class="order-chip">{{ $video->display_order }}</span></td>
                    <td>
                        <div class="person-cell">
                            <img src="{{ $video->thumbnailUrl() }}" alt="" class="service-thumb">
                            <div>
                                <span class="person-name">{{ $video->title }}</span>
                                <a href="{{ $video->watchUrl() }}" class="person-sub" target="_blank" rel="noopener">Open on YouTube</a>
                            </div>
                        </div>
                    </td>
                    <td>{{ $video->name ?: '—' }}</td>
                    <td>
                        <span class="status-chip status-{{ $video->is_active ? 'live' : 'hidden' }}">{{ $video->is_active ? 'Live' : 'Hidden' }}</span>
                    </td>
                    <td class="text-right">
                        <div class="row-actions">
                            <a href="{{ route('admin.videos.edit', $video) }}" class="btn-edit">Edit</a>
                            <form action="{{ route('admin.videos.destroy', $video) }}" method="POST" onsubmit="return confirm('Delete this video?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="table-empty">No video reviews yet. Add a YouTube link to show it on the home page.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <form method="POST" action="{{ route('admin.testimonials.youtube') }}" class="youtube-form">
        @csrf
        @method('PUT')
        <div class="form-row">
            <label for="youtube">YouTube channel link</label>
            <input type="url" id="youtube" name="youtube" value="{{ old('youtube', $youtube) }}" placeholder="https://www.youtube.com/@yourgym">
            <small>Used for the "Watch more on YouTube" button under the videos and the YouTube icon in the footer. Leave empty to hide both.</small>
            @error('youtube') <div class="alert-error">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn-admin-small">Save link</button>
    </form>

</div>
@endsection

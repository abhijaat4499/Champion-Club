@extends('layouts.admin-panel')

@section('title', 'Edit Video Review')

@section('content')
<div class="dashboard-wrap">

    <x-admin.page-header title="Edit Video Review" icon="videos">
        <a href="{{ route('admin.testimonials.index') }}#videos" class="btn-logout">&larr; Back to testimonials</a>
    </x-admin.page-header>

    <form method="POST" action="{{ route('admin.videos.update', $video) }}" class="plan-form">
        @csrf
        @method('PUT')

        @include('admin.videos._form', ['video' => $video])

        <button type="submit" class="btn-admin">Update Video</button>
    </form>
</div>
@endsection

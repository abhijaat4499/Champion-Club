@extends('layouts.admin-panel')

@section('title', 'Add Video Review')

@section('content')
<div class="dashboard-wrap">

    <x-admin.page-header title="Add Video Review" icon="videos">
        <a href="{{ route('admin.testimonials.index') }}#videos" class="btn-logout">&larr; Back to testimonials</a>
    </x-admin.page-header>

    <form method="POST" action="{{ route('admin.videos.store') }}" class="plan-form">
        @csrf

        @include('admin.videos._form')

        <button type="submit" class="btn-admin">Save Video</button>
    </form>
</div>
@endsection

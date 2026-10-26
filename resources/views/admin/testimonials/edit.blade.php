@extends('layouts.admin-panel')

@section('title', 'Edit Review')

@section('content')
<div class="dashboard-wrap">

    <x-admin.page-header title="Edit Review" icon="testimonials">
        <a href="{{ route('admin.testimonials.index') }}" class="btn-logout">&larr; Back to testimonials</a>
    </x-admin.page-header>

    <form method="POST" action="{{ route('admin.testimonials.update', $review) }}" class="plan-form">
        @csrf
        @method('PUT')

        @include('admin.testimonials._form', ['review' => $review])

        <button type="submit" class="btn-admin">Update Review</button>
    </form>
</div>
@endsection

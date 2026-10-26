@extends('layouts.admin-panel')

@section('title', 'Add Review')

@section('content')
<div class="dashboard-wrap">

    <x-admin.page-header title="Add Review" icon="testimonials">
        <a href="{{ route('admin.testimonials.index') }}" class="btn-logout">&larr; Back to testimonials</a>
    </x-admin.page-header>

    <form method="POST" action="{{ route('admin.testimonials.store') }}" class="plan-form">
        @csrf

        @include('admin.testimonials._form')

        <button type="submit" class="btn-admin">Save Review</button>
    </form>
</div>
@endsection

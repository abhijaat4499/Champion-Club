@extends('layouts.admin-panel')

@section('title', 'Edit Hero Slide')

@section('content')
<div class="dashboard-wrap">

    <x-admin.page-header title="Edit Hero Slide" icon="homepage">
        <a href="{{ route('admin.hero.index') }}" class="btn-logout">&larr; Back to homepage</a>
    </x-admin.page-header>

    <form method="POST" action="{{ route('admin.hero.update', $slide) }}" class="plan-form" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @include('admin.hero._form', ['slide' => $slide])

        <button type="submit" class="btn-admin">Update Slide</button>
    </form>
</div>
@endsection

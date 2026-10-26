@extends('layouts.admin-panel')

@section('title', 'Add Hero Slide')

@section('content')
<div class="dashboard-wrap">

    <x-admin.page-header title="Add Hero Slide" icon="homepage">
        <a href="{{ route('admin.hero.index') }}" class="btn-logout">&larr; Back to homepage</a>
    </x-admin.page-header>

    <form method="POST" action="{{ route('admin.hero.store') }}" class="plan-form" enctype="multipart/form-data">
        @csrf

        @include('admin.hero._form')

        <button type="submit" class="btn-admin">Save Slide</button>
    </form>
</div>
@endsection

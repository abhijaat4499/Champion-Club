@extends('layouts.admin-panel')

@section('title', 'Add Service')

@section('content')
<div class="dashboard-wrap">

    <x-admin.page-header title="Add Service" icon="services">
        <a href="{{ route('admin.services.index') }}" class="btn-logout">&larr; Back to services</a>
    </x-admin.page-header>

    <form method="POST" action="{{ route('admin.services.store') }}" class="plan-form" enctype="multipart/form-data">
        @csrf

        @include('admin.services._form')

        <button type="submit" class="btn-admin">Save Service</button>
    </form>
</div>
@endsection

@extends('layouts.admin-panel')

@section('title', 'Edit Service')

@section('content')
<div class="dashboard-wrap">

    <x-admin.page-header title="Edit Service" icon="services">
        <a href="{{ route('admin.services.index') }}" class="btn-logout">&larr; Back to services</a>
    </x-admin.page-header>

    <form method="POST" action="{{ route('admin.services.update', $service) }}" class="plan-form" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @include('admin.services._form', ['service' => $service])

        <button type="submit" class="btn-admin">Update Service</button>
    </form>
</div>
@endsection

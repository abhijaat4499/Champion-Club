@extends('layouts.admin-panel')

@section('title', 'Edit Facility')

@section('content')
<div class="dashboard-wrap">

    <x-admin.page-header title="Edit Facility" icon="facilities">
        <a href="{{ route('admin.facilities.index') }}" class="btn-logout">&larr; Back to facilities</a>
    </x-admin.page-header>

    <form method="POST" action="{{ route('admin.facilities.update', $facility) }}" class="plan-form" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @include('admin.facilities._form', ['facility' => $facility])

        <button type="submit" class="btn-admin">Update Facility</button>
    </form>
</div>
@endsection

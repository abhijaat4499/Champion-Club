@extends('layouts.admin-panel')

@section('title', 'Add Facility')

@section('content')
<div class="dashboard-wrap">

    <x-admin.page-header title="Add Facility" icon="facilities">
        <a href="{{ route('admin.facilities.index') }}" class="btn-logout">&larr; Back to facilities</a>
    </x-admin.page-header>

    <form method="POST" action="{{ route('admin.facilities.store') }}" class="plan-form" enctype="multipart/form-data">
        @csrf

        @include('admin.facilities._form')

        <button type="submit" class="btn-admin">Save Facility</button>
    </form>
</div>
@endsection

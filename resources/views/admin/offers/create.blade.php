@extends('layouts.admin-panel')

@section('title', 'Add Offer')

@section('content')
<div class="dashboard-wrap">

    <x-admin.page-header title="Add Offer" icon="offers">
        <a href="{{ route('admin.offers.index') }}" class="btn-logout">&larr; Back to offers</a>
    </x-admin.page-header>

    <form method="POST" action="{{ route('admin.offers.store') }}" class="plan-form">
        @csrf

        @include('admin.offers._form')

        <button type="submit" class="btn-admin">Save Offer</button>
    </form>
</div>
@endsection

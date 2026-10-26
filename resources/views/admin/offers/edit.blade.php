@extends('layouts.admin-panel')

@section('title', 'Edit Offer')

@section('content')
<div class="dashboard-wrap">

    <x-admin.page-header title="Edit Offer" icon="offers">
        <a href="{{ route('admin.offers.index') }}" class="btn-logout">&larr; Back to offers</a>
    </x-admin.page-header>

    <form method="POST" action="{{ route('admin.offers.update', $offer) }}" class="plan-form">
        @csrf
        @method('PUT')

        @include('admin.offers._form', ['offer' => $offer])

        <button type="submit" class="btn-admin">Update Offer</button>
    </form>
</div>
@endsection

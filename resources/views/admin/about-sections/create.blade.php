@extends('layouts.admin-panel')

@section('title', 'Add About Section')

@section('content')
<div class="dashboard-wrap">

    <x-admin.page-header title="Add About Section" icon="about">
        <a href="{{ route('admin.site.edit') }}#about-gym" class="btn-logout">&larr; Back to About &amp; Contact</a>
    </x-admin.page-header>

    <form method="POST" action="{{ route('admin.about-sections.store') }}" class="plan-form" enctype="multipart/form-data">
        @csrf

        @include('admin.about-sections._form')

        <button type="submit" class="btn-admin">Save Section</button>
    </form>
</div>
@endsection

@extends('layouts.admin-panel')

@section('title', 'Edit About Section')

@section('content')
<div class="dashboard-wrap">

    <x-admin.page-header title="Edit About Section" icon="about">
        <a href="{{ route('admin.site.edit') }}#about-gym" class="btn-logout">&larr; Back to About &amp; Contact</a>
    </x-admin.page-header>

    <form method="POST" action="{{ route('admin.about-sections.update', $section) }}" class="plan-form" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @include('admin.about-sections._form', ['section' => $section])

        <button type="submit" class="btn-admin">Update Section</button>
    </form>
</div>
@endsection

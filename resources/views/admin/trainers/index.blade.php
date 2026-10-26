@extends('layouts.admin-panel')

@section('title', 'Trainers')

@section('content')
<div class="dashboard-wrap">

    <x-admin.page-header title="Trainers" icon="trainers">
        <a href="{{ route('admin.trainers.create') }}" class="btn-admin-small">+ Add Trainer</a>
    </x-admin.page-header>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <table class="admin-table">
        <thead>
            <tr>
                <th class="col-order">Order</th>
                <th>Trainer</th>
                <th>Bio</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($trainers as $trainer)
                <tr>
                    <td class="col-order"><span class="order-chip">{{ $trainer->display_order }}</span></td>
                    <td>
                        <div class="person-cell">
                            @if($trainer->photo)
                                <img src="{{ asset('storage/' . $trainer->photo) }}" alt="{{ $trainer->name }}" class="trainer-thumb">
                            @else
                                <span class="trainer-thumb-placeholder">{{ $trainer->initials() }}</span>
                            @endif
                            <div>
                                <span class="person-name">{{ $trainer->name }}</span>
                                <span class="person-sub">{{ $trainer->role }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="col-bio">{{ \Illuminate\Support\Str::limit($trainer->bio, 90) }}</td>
                    <td class="text-right">
                        <div class="row-actions">
                            <a href="{{ route('admin.trainers.edit', $trainer) }}" class="btn-edit">Edit</a>

                            <form action="{{ route('admin.trainers.destroy', $trainer) }}" method="POST"
                                  onsubmit="return confirm('Delete this trainer?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="table-empty">No trainers yet.</td></tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection

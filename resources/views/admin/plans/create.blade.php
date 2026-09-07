@extends('layouts.admin')

@section('title', 'Add Plan')

@section('content')
<div class="dashboard-wrap">

    <header class="dashboard-header">
        <h1>Add Plan</h1>
    </header>

    <form method="POST" action="{{ route('admin.plans.store') }}" class="plan-form">
        @csrf

        <div class="form-row">
            <label for="name">Plan Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            @error('name') <div class="alert-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <label for="price">Price (₹)</label>
            <input type="number" id="price" name="price" value="{{ old('price') }}" required>
            @error('price') <div class="alert-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <label for="period">Billing Period</label>
            <input type="text" id="period" name="period" value="{{ old('period', '/month') }}" required>
        </div>

        <div class="form-row">
            <label for="features">Features (ek line mein ek feature)</label>
            <textarea id="features" name="features" rows="5" required>{{ old('features') }}</textarea>
            @error('features') <div class="alert-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <label for="color">Badge Color</label>
            <select id="color" name="color" required>
                <option value="bronze" {{ old('color') == 'bronze' ? 'selected' : '' }}>Bronze</option>
                <option value="gold" {{ old('color') == 'gold' ? 'selected' : '' }}>Gold</option>
                <option value="silver" {{ old('color') == 'silver' ? 'selected' : '' }}>Silver</option>
            </select>
        </div>

        <div class="form-row">
            <label for="button_text">Button Text</label>
            <input type="text" id="button_text" name="button_text" value="{{ old('button_text', 'Choose Plan') }}" required>
        </div>

        <div class="form-row">
            <label for="display_order">Display Order</label>
            <input type="number" id="display_order" name="display_order" value="{{ old('display_order', 0) }}" required>
        </div>

        <div class="checkbox-field">
            <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
            <label for="is_featured">Mark as "Most Popular"</label>
        </div>

        <button type="submit" class="btn-admin">Save Plan</button>
    </form>
</div>
@endsection
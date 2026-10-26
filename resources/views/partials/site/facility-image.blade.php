@if($facility->imageUrl())
    <img src="{{ $facility->imageUrl() }}" style="{{ $facility->imageFocus() }}" alt="{{ $facility->title }}" width="1200" height="900" loading="lazy" decoding="async" class="object-cover {{ $class }}">
@else
    @img(\App\Models\Facility::FALLBACK_IMAGE, $class, false, $facility->title)
@endif

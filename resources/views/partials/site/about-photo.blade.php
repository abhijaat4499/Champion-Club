@if(isset($photo['url']))
    <img src="{{ $photo['url'] }}" alt="{{ $alt }}" width="900" height="1200" loading="lazy" decoding="async" class="object-cover {{ $class }}">
@else
    @img($photo['site'], $class, false, $alt)
@endif

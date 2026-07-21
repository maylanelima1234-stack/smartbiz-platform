@props(['variant' => 'primary', 'href' => null, 'type' => 'button'])
@php($class = 'sb-btn sb-btn-' . $variant)
@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $class]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $class]) }}>{{ $slot }}</button>
@endif

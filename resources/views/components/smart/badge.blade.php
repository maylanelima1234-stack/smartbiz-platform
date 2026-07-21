@props(['variant' => 'neutral'])
<span {{ $attributes->merge(['class' => 'sb-badge sb-badge-' . $variant]) }}>{{ $slot }}</span>

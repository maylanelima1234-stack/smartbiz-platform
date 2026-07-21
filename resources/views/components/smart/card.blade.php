@props(['title' => null, 'subtitle' => null])
<div {{ $attributes->merge(['class' => 'sb-card']) }}>
    @if($title || $subtitle || isset($actions))
        <div class="sb-card-header">
            <div>
                @if($title)<h2 class="sb-card-title">{{ $title }}</h2>@endif
                @if($subtitle)<div class="small sb-muted mt-1">{{ $subtitle }}</div>@endif
            </div>
            @isset($actions)<div>{{ $actions }}</div>@endisset
        </div>
    @endif
    <div class="sb-card-body">{{ $slot }}</div>
</div>

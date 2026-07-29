@props(['value' => 0])

{{ \App\Core\Support\Money::parseBrazilian($value)->format() }}

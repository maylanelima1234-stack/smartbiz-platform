@props([
    'name',
    'value' => null,
    'id' => null,
    'required' => false,
    'disabled' => false,
])

@php
    use App\Core\Support\Money;

    $fieldId = $id ?: $name;
    $rawValue = old($name, $value);
    $displayValue = $rawValue === null || $rawValue === ''
        ? ''
        : Money::parseBrazilian((string) $rawValue)->format();
@endphp

<input
    id="{{ $fieldId }}"
    name="{{ $name }}"
    type="text"
    inputmode="numeric"
    autocomplete="off"
    data-smart-money
    value="{{ $displayValue }}"
    @required($required)
    @disabled($disabled)
    {{ $attributes->merge(['class' => 'mt-1 w-full rounded-xl border-slate-300']) }}
>

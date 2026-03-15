@props([
    'name',
    'id' => null,
    'placeholder' => '-- Pilih --',
    'required' => false,
    'disabled' => false,
    'value' => null,
    'options' => [],
])

@php
    $id = $id ?? $name;
    $classes = 'searchable-select w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200';

    if ($errors->has($name)) {
        $classes .= ' border-red-500';
    }
@endphp

<select
    name="{{ $name }}"
    id="{{ $id }}"
    {{ $required ? 'required' : '' }}
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => $classes]) }}
>
    @if($placeholder)
    <option value="">{{ $placeholder }}</option>
    @endif

    {{ $slot }}
</select>

@error($name)
<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror

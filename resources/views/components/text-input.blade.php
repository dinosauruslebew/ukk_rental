@props(['disabled' => false])

<input
    @disabled($disabled)
    {{ $attributes->merge([
        'class' =>
            'border-gray-300 bg-white text-gray-800 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm'
    ]) }}
>

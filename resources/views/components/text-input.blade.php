@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-700 bg-gray-800 text-white focus:border-accent-blue focus:ring-accent-blue rounded-md shadow-sm']) }}>
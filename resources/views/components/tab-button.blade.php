<button type="button" {{ $attributes->merge(['class' => isset($active) && $active ? 'inline-block px-4 py-2 text-white border-b-2 border-blue-500 bg-blue-500 bg-opacity-20 font-bold' : 'inline-block px-4 py-2 text-gray-400 border-b-2 border-transparent hover:border-gray-300']) }}>
    {{ $slot }}
</button>
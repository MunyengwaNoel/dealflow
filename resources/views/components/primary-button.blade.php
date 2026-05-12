<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-blue-600/20 transition-colors hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-600/25 focus:outline-none focus-visible:ring-4 focus-visible:ring-blue-500/35 active:bg-blue-800 disabled:pointer-events-none disabled:opacity-50',
]) }}>
    {{ $slot }}
</button>

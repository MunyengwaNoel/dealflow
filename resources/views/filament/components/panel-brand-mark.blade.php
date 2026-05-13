@php
    $name = config('app.name', 'DealFlow Pro');
    $alt = __('filament-panels::layout.logo.alt', ['name' => $name]);
@endphp
<img
    src="{{ url('images/dealflow-logo.svg') }}"
    alt="{{ $alt }}"
    class="fi-panel-brand-mark h-auto w-auto max-w-[min(100%,14rem)] object-contain object-left dark:hidden"
    style="height: {{ $height }}; max-height: {{ $height }}"
    width="268"
    height="86"
    decoding="async"
/>
<img
    src="{{ url('images/dealflow-logo-on-dark.svg') }}"
    alt="{{ $alt }}"
    class="fi-panel-brand-mark hidden h-auto w-auto max-w-[min(100%,14rem)] object-contain object-left dark:block"
    style="height: {{ $height }}; max-height: {{ $height }}"
    width="268"
    height="86"
    decoding="async"
/>

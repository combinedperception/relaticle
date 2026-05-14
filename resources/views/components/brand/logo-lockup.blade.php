@props([
    'showWordmark' => true,
    'size' => 'md',
])

@php
    $sizeMap = [
        'sm' => ['icon' => 'w-7 h-7 rounded-md',   'text' => 'text-[12px]', 'gap' => 'gap-2'],
        'md' => ['icon' => 'w-8 h-8 rounded-[7px]', 'text' => 'text-[13px]', 'gap' => 'gap-2.5'],
        'lg' => ['icon' => 'w-10 h-10 rounded-lg',  'text' => 'text-base',   'gap' => 'gap-3'],
    ];
    $s = $sizeMap[$size] ?? $sizeMap['md'];
@endphp

<div {{ $attributes->class("inline-flex items-center {$s['gap']}") }}>
    {{-- CP gradient badge --}}
    <div class="{{ $s['icon'] }} flex items-center justify-center flex-shrink-0"
         style="background: linear-gradient(135deg, #6171F7 0%, #10BAE9 100%);">
        <svg width="55%" height="55%" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <text x="1" y="14" font-family="'Archivo Black', system-ui, sans-serif"
                  font-size="13" font-weight="900" fill="white" letter-spacing="-0.5">CP</text>
        </svg>
    </div>

    @if ($showWordmark)
        <span class="{{ $s['text'] }} font-black leading-tight"
              style="font-family: 'Archivo Black', system-ui, sans-serif; letter-spacing: -0.02em;">
            Combined<br><span style="color: #10BAE9;">Perception</span>
        </span>
    @endif
</div>

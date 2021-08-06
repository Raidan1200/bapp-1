@props(['height' => '5', 'width' => '5'])

<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
  {{ $attributes->merge([
    'class' => "h-$height w-$width"
  ]) }}>
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
</svg>

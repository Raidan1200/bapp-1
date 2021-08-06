@props(['height' => '5', 'width' => '5'])

<svg
  {{ $attributes->merge([
    'class' => "h-$height w-$width"
  ]) }}
  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
>
  {{ $slot }}
</svg>

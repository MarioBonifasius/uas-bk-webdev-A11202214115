@props(['active' => false, 'label' => ''])

<button {{ $attributes->merge([
  'class' => 'btn btn-sm rounded-full px-6 normal-case font-medium transition-all ' .
    ($active
      ? 'btn-primary border-blue-900 bg-blue-900 text-white hover:bg-blue-800'
      : 'btn-outline border-blue-900 text-blue-900 hover:bg-blue-900 hover:text-white')
]) }}>
  {{ $label }}
</button>
@props(['type' => 'success', 'message'])

@php
    $colors = [
        'success' => 'bg-green-500 dark:bg-green-600',
        'error' => 'bg-red-500 dark:bg-red-600',
        'info' => 'bg-blue-500 dark:bg-blue-600',
    ];
@endphp

<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
    class="fixed top-4 right-4 z-50 {{ $colors[$type] ?? 'bg-gray-500' }} text-white px-4 py-2 rounded-lg shadow-lg">
    {{ $message }}
</div>

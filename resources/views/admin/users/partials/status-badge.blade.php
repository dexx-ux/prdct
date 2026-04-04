{{-- resources/views/admin/users/partials/status-badge.blade.php --}}
@php
    $status = $status ?? 'active';
@endphp

<span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
    @if($status === 'active')
        bg-green-100 text-green-800 dark:bg-green-900
    @else dark:text-red-200
    @endif">
    <i class="bi bi-{{ $status === 'active' ? 'check-circle' : 'x-circle' }} mr-1 text-xs"></i>
    {{ ucfirst($status) }}
</span>
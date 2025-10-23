
    <div class="flex items-center gap-2">
        <label class="text-sm text-gray-500 dark:text-gray-400">Location</label>
        <select
            wire:model.live="activeLocationId"
            class="fi-input block rounded-lg border-gray-300 dark:border-gray-600 text-sm"
            style="min-width: 12rem;"
            @class(['dark:bg-gray-800 dark:text-gray-100'])
        >
            @foreach ($options as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
            @endforeach
        </select>
    </div>
    
@push('scripts')
<script>
    window.addEventListener('reload-page', () => {
        location.reload(); // Reload halaman saat ini
    });
</script>
@endpush

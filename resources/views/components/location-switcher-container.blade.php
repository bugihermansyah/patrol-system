<div class="pl-3">
    <livewire:location-switcher />
</div>

    <script>
        document.addEventListener('livewire:load', () => {
            Livewire.on('reload-page', () => {
                location.reload(); // reload halaman yang sama
            });
        });
    </script>
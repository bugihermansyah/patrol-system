<?php

namespace App\Livewire;

use Livewire\Component;

class LocationSwitcher extends Component
{
    public $activeLocationId;

    public function mount()
    {
        $this->activeLocationId = session('active_location_id');
    }

    public function updatedActiveLocationId()
    {
        $user = auth()->user();

        // Cek apakah user terautentikasi
        abort_unless($user, 403, 'User not authenticated');

        $isMember = $user->locations()
            ->where('locations.id', $this->activeLocationId)
            ->exists();

        abort_unless($isMember, 403, 'You are not a member of this location');

        session(['active_location_id' => $this->activeLocationId]);

        $this->dispatch('reload-page');
    }

    public function render()
    {
        $user = auth()->user();

        $options = $user
            ? $user->locations()->pluck('name', 'locations.id')->toArray()
            : [];

            return view('livewire.location-switcher', compact('options'));
    }
}

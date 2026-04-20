<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class RawMaterial extends Component
{
    public function render()
    {
        return view('livewire.raw-material', [
            'raw_material' => DB::table('v_raw_material')->get(),
            'store' => DB::table('store')->get(),
            'raw_material_usages_store' => DB::table('v_raw_material_usages_store')->get()
        ]);
    }
}

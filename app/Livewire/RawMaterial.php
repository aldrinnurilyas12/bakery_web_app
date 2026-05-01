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
            'checking_material_usages' => DB::table('raw_material as rw')
                                ->join('raw_material_usages as ru', 'rw.material_code', '=', 'ru.raw_material')
                                ->whereNotNull('ru.quantity_used')->get(),
            'store' => DB::table('store')->get(),
            'raw_material_usages_store' => DB::table('v_raw_material_usages_store')->get()
        ]);
    }
}

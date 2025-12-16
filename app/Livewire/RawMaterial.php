<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class RawMaterial extends Component
{
    public function render()
    {
        return view('livewire.raw-material', [
            'raw_material' => DB::table('raw_material as rm')
                            ->leftJoin('status_category as s','rm.status', '=', 's.id')->get()
        ]);
    }
}

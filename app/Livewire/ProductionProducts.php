<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ProductionProducts extends Component
{
    public function render()
    {
        return view('livewire.production-products', [
            'production_products' => DB::table('v_production_products')->get(),
            'status' => DB::table('status_category')->whereIn('id', ['2','3', '4', '5', '9', '10'])->get()
        ]);
    }
}

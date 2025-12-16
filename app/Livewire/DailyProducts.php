<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class DailyProducts extends Component
{
     public function render()
    {
        return view('livewire.daily-products', [
        'daily_products' => DB::table('v_daily_products')->get()
    ]);
    }
}

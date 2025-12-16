<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Voucher extends Component
{
    public function render()
    {
        return view('livewire.voucher', [
            'vouchers' => DB::table('voucher')->get()
        ]);
    }
}

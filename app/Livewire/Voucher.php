<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Voucher extends Component
{
    public function render()
    {
        return view('livewire.voucher', [
            'vouchers' => DB::table('voucher')->orderBy('created_at', 'DESC')->get(),
            'cek_redeem_voucher' => DB::table('voucher as v')->join('redeem_voucher as rv', 'v.voucher_code', '=', 'rv.voucher_code')->first()
        ]);
    }
}

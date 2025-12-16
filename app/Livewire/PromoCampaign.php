<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class PromoCampaign extends Component
{
    public function render()
    {
        return view('livewire.promo_campaign', [
            'promo_campaign' => DB::table('v_promos')->get()
        ]);
    }
}

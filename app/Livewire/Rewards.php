<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Rewards extends Component
{
    public function render()
    {
        return view('livewire.rewards', [
            'rewards' => DB::table('rewards as r')
                ->leftJoin('status_category as s', 'r.status', '=', 's.id')->get()
        ]);
    }
}

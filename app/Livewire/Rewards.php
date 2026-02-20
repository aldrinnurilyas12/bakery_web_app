<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Rewards extends Component
{
    public $filter;
    public $daily_products;
    public $store;

    public function mount()
    {
        // Inisialisasi data store
        $this->filter = request()->query('filter', '');
        $this->store = DB::table('store')->get();
        
    }


    public function render()
    {
        $filteredProduction = DB::table('v_rewards');
        $store = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_id;

        if(empty($this->filter)){
             $filteredProduction->where('store_id',$store);
        }
        elseif (!empty($this->filter) && $this->filter !== 'all') {
            $filteredProduction->where('store_id', $this->filter);
        } 

        $this->rewards = $filteredProduction->get();

        return view('livewire.rewards', [
            'store' => DB::table('store')->get(),
            'rewards' => $this->rewards
        ]);
    }

    public function updateFilter() 
    {
        $this->render();
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class DailyProducts extends Component
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
        $filteredProducts = DB::table('v_daily_products');
        $store = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_code;

        if(empty($this->filter)){
             $filteredProducts->where('store_code',$store);
        }
        elseif (!empty($this->filter) && $this->filter !== 'all') {
            $filteredProducts->where('store_code', $this->filter);
        } 

        $this->daily_products = $filteredProducts->get();

        return view('livewire.daily-products', [
            'daily_products' => $this->daily_products,
            'store' => $this->store
        ]);
    }

    public function updatedFilter()
    {
        $this->render(); // Memastikan render ulang saat filter diperbarui
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ProductionProducts extends Component
{
    public $filter;
    public $daily_products;
    public $store;

    public function mount()
    {
        // Inisialisasi data store
        $this->filter = request()->query('fstore', '');
        $this->store = DB::table('store')->get();
        
    }

    public function render()
    
    {
        $filteredProduction = DB::table('v_production_products');
        $store = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_code;

        if(empty($this->filter)){
             $filteredProduction->where('store_code',$store);
        }
        elseif (!empty($this->filter) && $this->filter !== 'all') {
            $filteredProduction->where('store_code', $this->filter);
        } 

        $this->production_product = $filteredProduction->get();

        return view('livewire.production-products', [
            'production_products' => $this->production_product,
            'status' => DB::table('status_category')->whereIn('id', ['2','3', '4', '5', '9', '10'])->get(),
            'store' => DB::table('store')->get()]);
    }

    public function updateFilter() 
    {
        $this->render();
    }
}

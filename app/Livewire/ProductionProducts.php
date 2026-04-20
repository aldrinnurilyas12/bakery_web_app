<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ProductionProducts extends Component
{
    public $filter_date = '';
    public $store = '';

    public function mount()
    {
        // ambil dari query string jika ada
        $this->store = request()->query('store', '');
        $this->filter_date = request()->query('filter_date', '');
    }

    public function render()
    {
        $user_store = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_code;

        // cegah akses role tertentu
        // if (in_array($user->role_name, ['Staff', 'Casheer'])) {
        //     abort(403);
        // }


        // jika tidak pilih store → pakai default
        $selectedStore = $this->store ?: $user_store;

        $query = DB::table('v_production_products')
                    ->orderByDesc('production_date');

        
        if($this->filter_date){
            switch ($this->filter_date) {

                case 'today':
                    $query->whereDate('production_date', Carbon::today());
                    break;

                case 'week':
                    $query->whereBetween('production_date', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]);
                    break;

                case 'month':
                    $query->whereMonth('production_date', Carbon::now()->month)
                        ->whereYear('production_date', Carbon::now()->year);
                    break;

                case 'year':
                    $query->whereYear('production_date', Carbon::now()->year);
                    break;
            }
        }else{
             $query->whereDate('production_date', Carbon::today());
        }

        

        $production_products = $query->get();


        return view('livewire.production-products', [
            'production_products' => $production_products,
            'stores' => DB::table('store')->get(),
            'status' => DB::table('status_category')
                        ->whereIn('id', [2,3,4,5,9,10])
                        ->get(),
        ]);
    }
}
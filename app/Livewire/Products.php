<?php

namespace App\Livewire;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Products extends Component
{
  
    public function render()
    {
        return view('livewire.products', [
        'products' => DB::table('v_products')->get()
    ]);
    }

   


   
}

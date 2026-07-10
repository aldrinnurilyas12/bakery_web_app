<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PromoBundling as ModelsPromoBundling;
use App\Models\PromoBundlingDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\UserLogActivity;
use Illuminate\Support\Str;
use Carbon\Carbon;


class PromoBundling extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $store = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_code;

        $bundling = DB::table('v_promo_bundling')->where('status', 7)->get();

        $all_product = DB::table('promo_bundling_detail as pbd')
                    ->select('pbd.quantity', 'pbd.bundling_code', 'p.product_name', 'p.product_code', 'vdp.stock_available')
                    ->leftJoin('products as p', 'pbd.product', '=', 'p.product_code')
                    ->leftJoin('v_daily_products as vdp', 'pbd.product', '=', 'vdp.product_code')
                    ->where('vdp.store_code', $store)
                    ->get();
        // dd($all_product);
        return view('layouts.main_pages.promo_bundling.promo_bundling', compact('bundling', 'all_product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $products = DB::table('v_daily_products as vdp')
            ->select('vdp.product_code','vdp.variant_code', 'vdp.product', 'pi.images')
            ->leftJoin('product_images as pi', 'vdp.product_code', 'pi.product_code')
            ->where('vdp.status', 'Ready')->distinct()->get();
         return view('layouts.main_pages.promo_bundling.create.promo_bundling_create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'bundling_name' => 'required',
            'price' => 'required',
            'quantity_promo' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'description' => 'required',
            'images'=> 'required|mimes:jpg,png,jpeg|max:5000',
            'product' => 'array',
            'variant' => 'array',
            'quantity' => 'array'
        ],
        [
            'bundling_name.required' => 'Nama Promo Bundling harus dissi',
            'price.required' => 'Harga Promo Bundling harus diisi',
            'quantity_promo.required' => 'Total quantity harus diisi',
            'start_date.required' => 'Tanggal awal Promo harus diisi',
            'end_date.required' => 'Tanggal akhir promo harus diisi',
            'description.required' => 'Deskripsi promo harus diisi',
            'images.required' => 'Gambar harus diupload'
        ]);

        $created_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        $date = now()->format('Ymd');
        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 6);
        $bundling_code = 'BUNDLING'.'-'. $date . $unique_code;

        $productSelect = $request->product;
        $quantities   = $request->quantity;


        if ($request->hasFile('images')) {
                $promo_image = $request->file('images');
                $folderPath = 'promo_bundling/' . $bundling_code;
                $imagePath = $promo_image->storeAs($folderPath, uniqid() . '.' . $promo_image->getClientOriginalExtension(), 'public');

                $data = [
                    'bundling_code'=> $bundling_code,
                    'bundling_name' => $request->bundling_name,
                    'price' => $request->price,
                    'quantity' => $request->quantity_promo,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'description' => $request->description,
                    'images'=> $imagePath,
                    'status' => 7,
                    'created_by' => $created_by,
                    'created_at' => now(),
                    'updated_at' => null
                ];

                ModelsPromoBundling::create($data);

                foreach($productSelect as $key => $productMany){
                    PromoBundlingDetail::create([
                        'bundling_code' => $bundling_code,
                        'product' => $productMany,
                        'variant' => $request->variant[$key] ?? null,
                        'quantity'=> (int) ($quantities[$key] ?? 0)
                    ]);
                }
                
        }

         UserLogActivity::log(
                module: 'Promo Bundling',
                method_type: 'CREATE',
                description: "user create new promo bundling: {$bundling_code}"      
        );

        session()->flash('message_success', 'Data Promo Bundling berhasil disimpan!');
        return redirect()->route('promo_bundling.index');

    }

    public function bundling_nonactive(Request $request)
    {

        $bundling_code = $request->bundling_code;

        ModelsPromoBundling::where('bundling_code', $bundling_code)->update([
            'status' => $request->status
        ]);

        session()->flash('message_success', 'Status Promo Bundling berhasil diperbarui!');
        return redirect()->back();

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

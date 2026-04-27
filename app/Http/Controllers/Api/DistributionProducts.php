<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CentralStockProductsModel;
use App\Models\DistributionProductsDetailModel;
use App\Models\DistributionProductsModel;
use App\Models\ProductWaste;
use App\Models\ProductWasteDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class DistributionProducts extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $distribution = DB::table('distribution_products as dp')
                        ->select('dp.distribution_code', 'dp.distribution_date', 'dp.attachment_file','sc.status_name', 'e.name as emp_name', 'emp.name as employee_name', 'dp.created_at', 'dp.updated_at')
                        ->leftJoin('status_category as sc', 'dp.status', '=', 'sc.id')
                        ->leftJoin('employee as e', 'dp.created_by', '=', 'e.nik')
                        ->leftJoin('employee as emp', 'dp.updated_by', '=', 'emp.nik')
                        ->orderBy('dp.created_at', 'DESC')->get();
        return view('layouts.main_pages.distribution_products.distribution', compact('distribution'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function distribution_create()
    {
        $products = DB::table('v_central_stock_products')->get();
        $stores = DB::table('store')->get();
        return view('layouts.main_pages.distribution_products.create.distribution_create', compact('products', 'stores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    
    public function store(Request $request)
    {
            $request->validate([
                'distribution_date' => 'required'
            ],
            [
                'distribution_date.required' => 'Pilih tanggal distribusi dahulu'
            ]);


            $date = now()->format('Ymd');
            $uuid = (string) Str::uuid();
            $unique_code = substr($uuid, 0, 6);
            $distribution_code = 'DS-'.$date.'-'. $unique_code;
            $emp =app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;

        DB::beginTransaction();

    try {

        if (!$request->has('product')) {
            throw new \Exception('Data produk tidak ditemukan');
        }

        $distribution = DistributionProductsModel::create([
            'distribution_code' => $distribution_code,
            'distribution_date' => $request->distribution_date,
            'status' => 19,
            'created_by' => $emp,
            'created_at' => now()
        ]);

        foreach ($request->product as $i => $product) {

            $productId   = $product['product_code'];
            $variantId   = $product['variant_code'] ?? null;
            $expiredDate = $product['expired_date'] ?? null;

            // ambil stock sekali saja per product (lebih efisien)
            $stockQuery = CentralStockProductsModel::where('product', $productId);

            if (!empty($variantId)) {
                $stockQuery->where('variant', $variantId);
            } else {
                $stockQuery->whereNull('variant');
            }

            $stock = $stockQuery->lockForUpdate()->first();

            if (!$stock) {
                throw new \Exception("Stock tidak ditemukan untuk product $productId");
            }

            // total distribusi untuk 1 product
            $totalRequestQty = 0;

            if (!isset($product['store'])) {
                continue;
            }

            foreach ($product['store'] as $storeId => $qty) {

                if (empty($qty) || $qty <= 0) {
                    continue;
                }

                if ($qty < 0) {
                    throw new \Exception("Qty tidak boleh minus (product $productId, store $storeId)");
                }

                $totalRequestQty += $qty;
            }

            foreach ($product['store'] as $storeId => $qty) {

                if (empty($qty) || $qty <= 0) {
                    continue;
                }

                $distribution_store_code = 'DST-' . date('Ymd') . '-' . substr(Str::uuid(), 0, 6);

                DistributionProductsDetailModel::create([
                    'distribution_store_code' => $distribution_store_code,
                    'distribution' => $distribution->distribution_code,
                    'product' => $productId,
                    'variant' => $variantId,
                    'quantity' => $qty,
                    'expired_date' => $expiredDate,
                    'store' => $storeId,
                    'status' => 21
                ]);
            }

            $stock->decrement('qty_available', $totalRequestQty);
        }

        DB::commit();

        return redirect()->route('distribution_products.index')
            ->with('message_success', 'Data Distribusi Produk berhasil disimpan!');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->back()
                ->with('failed_message', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function distribution_detail_layouts(Request $request)
    {
        $store = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->store_name;
        $wastes_category = DB::table('waste_category')->get();
        $distribution_detail = DB::table('v_distribution_detail')->where('distribution', $request->distribution_code)->where('store_name', $store)->get();
        return view('layouts.main_pages.distribution_products.distribution_detail', compact('distribution_detail', 'wastes_category'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $request->validate([
            'attachment_files' => 'image|mimes:jpg,png,jpeg|max:5000',
        ]);

        $checking_status = DB::table('v_distribution_detail')->where('distribution_store_code', $request->distribution_store_code)->first();

        if($checking_status->status_name == 'Received'){
            session()->flash('failed_message', 'Tidak bisa update data ini!');
            return redirect()->back(); 
        }

        $approval = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 5);
        $wasteCode = 'WASTE' . $unique_code;
        $user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;


        if($request->waste_confirmation == 'yes'){

            DistributionProductsDetailModel::where('distribution_store_code', $request->distribution_store_code)->update([
                        'received_quantity' => $request->received_quantity,
                        'status' => 20,
                        'approval' => $approval,
                        'updated_at' =>now()
                ]);
            return redirect()->route('product-waste-distribution', $request->distribution);
        }else{
             DistributionProductsDetailModel::where('distribution_store_code', $request->distribution_store_code)->update([
                        'received_quantity' => $request->received_quantity,
                        'status' => 20,
                        'approval' => $approval,
                        'updated_at' =>now()
                ]);
        }

        session()->flash('message_success', 'Berhasil menyimpan data!');
        return redirect()->back();
    }


    public function product_waste_distribution_save(Request $request)
    {

        $request->validate([
            'attachment_files' => 'image|mimes:jpg,png,jpeg|max:5000',
            'waste_type'  => 'required|array',
            'waste_type*'=> 'nullable|integer|min:0'
        ]);


        $approval = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 5);
        $wasteCode = 'WASTE' . $unique_code;
        $user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;

        if ($request->hasFile('attachment_files')) {
                $distributionFile = $request->file('attachment_files');
                $folderPath = 'distribution_reject_file/' . $request->distribution;
                $distributionPath = $distributionFile->storeAs($folderPath, uniqid() . '.' . $distributionFile->getClientOriginalExtension(), 'public');
                DistributionProductsDetailModel::where('distribution_store_code', $request->distribution)->update([
                        'reject_quantity' => $request->reject_quantity,
                        'notes' => $request->notes,
                        'attachment_files' => $distributionPath,
                        'status' => 20,
                        'approval' => $approval,
                        'updated_at' =>now()
                    ]);

                $codeWastes = ProductWaste::create([
                    'distribution' => $request->distribution,
                    'waste_code' => $wasteCode,
                    'waste_date' => now(),
                    'reason' => $request->reason,
                    'status' => 13,
                    'approved_by' => null,
                    'created_by' => $user,
                ]);
                


            foreach($request->waste_type as $waste => $qty){


                if (!$qty || $qty <= 0) {
                    continue;
                }
                $waste_type = DB::table('waste_category')->select('waste_code')->where('waste_code', $waste)->first();

                ProductWasteDetail::create([
                    'waste_code' => $codeWastes->waste_code,
                    'waste_type' => $waste_type->waste_code,
                    'quantity' => $qty,
                ]);
             }
        }

        session()->flash('message_success', 'Berhasil menyimpan data!');
        return redirect()->route('distribution_detail', $request->distribution_code);

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

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ItemPriceDetailModel;
use App\Models\PurchaseOrderDetailModel;
use App\Models\PurchaseOrderModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchase_order = DB::table('purchase_order as po')
        ->select('po.purchase_code','po.purchase_date', 'po.payment_invoice','po.total_amount','po.delivery', 'po.expected_delivery_date', 'po.created_at', 'po.updated_at', 'sp.store as supplier_name', 'e.name', 'emp.name', 'sc.status_name')
        ->leftJoin('supplier as sp', 'po.supplier', '=', 'sp.supplier_code')
        ->leftJoin('status_category as sc', 'po.status', '=', 'sc.id')
        ->leftJoin('employee as e', 'po.created_by', '=', 'e.nik')
        ->leftJoin('employee as emp', 'po.updated_by', '=', 'emp.nik')
        ->orderBy('created_at', 'DESC')->get();

        return view('layouts.main_pages.purchase_order.purchase_order', compact('purchase_order'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }
        $supplier = DB::table('supplier')->where('status', 7 )->get();
        $items = DB::table('items as i')
        ->leftJoin('item_category as ic', 'i.item_category', '=', 'ic.id')->get();
        return view('layouts.main_pages.purchase_order.create.purchase_order', compact('supplier', 'items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'purchase_date' => 'required',
            'total_amount' => 'required',
            'supplier' => 'required',
            'payment_invoice' => 'image|required| mimes: jpg,png,jpeg|max:5000'
        ],
        [
            'supplier.required' => 'Pilih Supplier dahulu',
            'purchase_date.required'=> 'Tanggal PO harus diisi',
            'total_amount.required'=> 'Total biaya harus diisi',
            'payment_invoice.required' => 'Upload bukti pembayaran'
        ]);

        $date = now()->format('Ymd');
        $uuid = (string) Str::uuid();
        $unique_code = substr($uuid, 0, 6);
        $po_code = 'PO-'.$date.'-'. $unique_code;
        $emp =app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;

        $supplier_code = $request->supplier;
        $items = $request->item;
        $rawMaterial = $request->raw_material;
        $quantities = $request->quantity;
        $price = $request->price;

        if ($request->hasFile('payment_invoice')) {
                $po_invoice = $request->file('payment_invoice');
                $folderPath = 'purchase_order_invoice/' . $po_code;
                $imagePath = $po_invoice->storeAs($folderPath, uniqid() . '.' . $po_invoice->getClientOriginalExtension(), 'public');

           $po = PurchaseOrderModel::create([
                'purchase_code' => $po_code,
                'purchase_date' => $request->purchase_date,
                'supplier' => $request->supplier,
                'status' => 17,
                'delivery' => $request->delivery,
                'total_amount' => $request->total_amount,
                'payment_invoice' => $imagePath,
                'expected_delivery_date' => $request->expected_delivery_date ?? null,
                'created_at' => now(),
                'created_by' => $emp,
                'updated_at' => now(),
                'updated_by' => $emp
            ]);


            $itemType = DB::table('items as i')
                ->leftJoin('item_category as ic', 'i.item_category', '=', 'ic.id')
                ->where('i.item_code', $items)
                ->value('ic.category_name');
           

                foreach($items as $itemCode){
                    $rawCode =$rawMaterial[$itemCode] ?? null;
                    PurchaseOrderDetailModel::create([
                        'purchase_code' =>$po->purchase_code,
                        'item' =>$itemCode,
                        'raw_material' => $rawCode,
                        'quantity' =>(int) ($quantities[$itemCode] ?? 0),
                        'price' => (int) ($price[$itemCode]??0)
                    ]);

                    ItemPriceDetailModel::create([
                        'item' =>$itemCode,
                        'price' => (int) ($price[$itemCode]??0)
                    ]);
                  
                }
        }

        session()->flash('message_success', 'Data Purchase Order berhasil disimpan!');
        return redirect()->route('purchase_order.index');
    }

    
    public function get_category(Request $request)
    {
        $supplier_code = $request->supplier;
        $data = DB::table('supplier as sp')
                ->select('sc.category_name')
                ->leftJoin('supplier_category as sc', 'sp.supplier_category', '=', 'sc.id')
                ->where('sp.supplier_code', $supplier_code)
                ->get();

        return response()->json([
            'data' => $data,
            'message' => 'Data category supplier'
        ]);
    }

    public function get_detail(Request $request)
    {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Supervisor', 'Manager']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }
        $purchase_order = DB::table('v_purchase_order')->where('purchase_code', $request->purchase_code)->get();
        return view('layouts.main_pages.purchase_order.purchase_order_detail', compact('purchase_order'));
    }


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

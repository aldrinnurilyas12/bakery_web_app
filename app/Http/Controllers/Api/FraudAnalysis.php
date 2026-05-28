<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\FraudTransactions;
use App\Models\FraudTransactionTimeline;

class FraudAnalysis extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $session_user = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers();
        $user_permission_forbidden = in_array($session_user->role_name , ['Casheer']);
        if($user_permission_forbidden){
            session()->flash('failed_message', 'Tidak bisa akses');
            return redirect()->back();
        }

        $status_category = DB::table('status_category')->whereIn('id', ['23','25'])->get();
        $check_fraud_timeline = DB::table('fraud_transactions_timeline as ftt')
                        ->select('ftt.fraud')
                        ->join('fraud_transactions as ft', 'ftt.fraud', '=', 'ft.fraud_code')->first();
        $fraud_transactions = DB::table('v_fraud_transactions')->orderBy('transaction_date', 'DESC')->get();
        $status_fraud = DB::table('fraud_status_info')->whereIn('id',['1', '2'])->get();

        $fraud_timeline = DB::table('fraud_transactions_timeline as ftl')
            ->select('ftl.fraud', 'sc.status_name', 'ftl.updated_at', 'e.name', 'fsi.info_name')
            ->join('fraud_transactions as ft', 'ftl.fraud', '=', 'ft.fraud_code')
            ->leftjoin('status_category as sc', 'ftl.status', '=', 'sc.id')
            ->leftJoin('employee as e', 'ftl.updated_by', '=', 'e.nik')
            ->leftJoin('fraud_status_info as fsi', 'ft.fraud_status_info', '=', 'fsi.id')->get();

        return view('layouts.main_pages.fraud_transactions.fraud_transactions', compact('fraud_transactions','fraud_timeline','status_category', 'check_fraud_timeline', 'status_fraud'));
    }


    public function update_status_fraud(Request $rq)
    {

        $created_by = app('App\Http\Controllers\Auth\AuthenticatedSessionController')->getUsers()->nik;
        
        FraudTransactions::where('fraud_code', $rq->fraud_code)->update([
            'status' => $rq->status_progress,
            'fraud_status_info' => $rq->fraud_status_info,
            'notes' => $rq->notes,
            'investigation_by' => $rq->investigation_by,
            'approval_by' => $rq->approval_by
        ]);
        
        FraudTransactionTimeline::create([
            'fraud' => $rq->fraud_code,
            'status' => $rq->status_progress,
            'updated_by' => $created_by
        ]);

        session()->flash('message_success', 'Data berhasil diperbarui!');
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Saler;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SalerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Saler::query();
        if ($request->search) {
            $cari = $request->search;
            $query->whereHas('status', function ($query) use ($cari) {
                $query->where('status', 'like', '%' . $cari . '%');
            })->orwhereHas('customer', function ($query) use ($cari) {
                $query->where('nama', 'like', '%' . $cari . '%');
            });
        }
        $saler = $query->with(['status', 'customer'])->paginate(15)->withQueryString();
        $customer = Customer::all();
        $status = Status::all();
        return response()->json(['salers' => $saler, 'customers' => $customer, 'statuses' => $status]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'customer' => 'required|exists:customers,id',
            'tonase' => 'nullable|decimal:0,5',
            'tonasegp' => 'nullable|decimal:0,5',
            'harga' => 'nullable|decimal:0,5',
            'hargagp' => 'nullable|decimal:0,5',
            'kasbon' => 'nullable|decimal:0,5',
            'bongkar' => 'nullable|decimal:0,5',
            'mati' => 'nullable|decimal:0,5'
        ])->validate();
        Saler::create([
            'customer_id' => $valid['customer'],
            'status_id' => 2,
            'tonase' => $valid['tonase'],
            'tonase_gp' => $valid['tonasegp'],
            'harga' => $valid['harga'],
            'harga_gp' => $valid['hargagp'],
            'kasbon' => $valid['kasbon'],
            'bongkar' => $valid['bongkar'],
            'mati' => $valid['mati'],
            'jumlah' => ($valid['tonase'] * $valid['harga']) + ($valid['tonasegp']) - $valid['kasbon'] - $valid['bongkar'] - $valid['mati']
        ]);
        return response()->json(['message' => 'ok']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $sale = Saler::where('id', '=', $id)->first();
        $valid = Validator::make($request->all(), [
            'customer' => 'required|exists:customers,id',
            'tonase' => 'nullable|decimal:0,5',
            'tonasegp' => 'nullable|decimal:0,5',
            'harga' => 'nullable|decimal:0,5',
            'hargagp' => 'nullable|decimal:0,5',
            'kasbon' => 'nullable|decimal:0,5',
            'bongkar' => 'nullable|decimal:0,5',
            'mati' => 'nullable|decimal:0,5',
            'status' => 'required|exists:statuses,id'
        ])->validate();
        $sale->update([
            'customer_id' => $valid['customer'],
            'status_id' => $valid['status'],
            'tonase' => $valid['tonase'],
            'tonase_gp' => $valid['tonasegp'],
            'harga' => $valid['harga'],
            'harga_gp' => $valid['hargagp'],
            'kasbon' => $valid['kasbon'],
            'bongkar' => $valid['bongkar'],
            'mati' => $valid['mati'],
            'jumlah' => ($valid['tonase'] * $valid['harga']) + ($valid['tonasegp']) - $valid['kasbon'] - $valid['bongkar'] - $valid['mati']
        ]);
        return response()->json(['message' => 'ok']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $sale = Saler::where('id', '=', $id)->first();
        $sale->delete();
    }
}

<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Purchase;
use App\Models\Status;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Purchase::query();
        if ($request->search) {
            $cari = $request->search;
            $query->whereHas('vendor', function ($query) use ($cari) {
                $query->where('nama', 'like', '%' . $cari . '%');
            })->orwhereHas('status', function ($query) use ($cari) {
                $query->where('status', 'like', '%' . $cari . '%');
            });
        }
        $purchases = $query->with(['status', 'driver', 'vendor'])->paginate(15)->withQueryString();
        $vendors = Vendor::all();
        $drivers = Driver::all();
        $status = Status::all();
        return response()->json(['purchases' => $purchases, 'vendors' => $vendors, 'drivers' => $drivers, 'statuses' => $status]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'vendor' => 'required|exists:vendors,id',
            'driver' => 'required|exists:drivers,id',
            'harga' => 'required|numeric',
            'tonase' => 'required|numeric'
        ])->validate();
        Purchase::create([
            'vendor_id' => $valid['vendor'],
            'driver_id' => $valid['driver'],
            'harga' => $valid['harga'],
            'tonase' => $valid['tonase'],
            'status_id' => 2,
            'jumlah' => $valid['harga'] * $valid['tonase']
        ]);
        return response()->json(['message' => 'ok']);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $purchase = Purchase::where('id', '=', $id)->with(['status', 'driver', 'vendor'])->first();
        return response()->json(['purchase' => $purchase]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $purchase = Purchase::where('id', $id)->first();
        $valid = Validator::make($request->all(), [
            'vendor' => 'required|exists:vendors,id',
            'driver' => 'required|exists:drivers,id',
            'harga' => 'required|numeric',
            'tonase' => 'required|numeric',
            'status' => 'required|exists:statuses,id'
        ])->validate();
        $purchase->update([
            'vendor_id' => $valid['vendor'],
            'driver_id' => $valid['driver'],
            'harga' => $valid['harga'],
            'tonase' => $valid['tonase'],
            'status_id' => $valid['status'],
            'jumlah' => $valid['harga'] * $valid['tonase']
        ]);
        return response()->json(['message' => 'ok']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $purchase = Purchase::where('id', $id)->first();
        $purchase->delete();
        return response()->json(['message' => 'ok']);
    }
}

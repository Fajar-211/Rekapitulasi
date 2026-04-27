<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\MethodePembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MethodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MethodePembayaran::query();
        if ($request->search) {
            $cari = $request->search;
            $query->where('methode', 'like', '%' . $cari . '%');
        }
        $methode = $query->paginate(10)->withQueryString();
        return response()->json(['methods' => $methode]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'methode' => 'required|unique:methode_pembayarans,methode|string'
        ])->validate();
        MethodePembayaran::create([
            'methode' => $valid['methode']
        ]);
        return response()->json(['message' => 'succes']);
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
    public function update(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'id' => 'required|exists:methode_pembayarans,id',
            'methode' => 'required|unique:methode_pembayarans,methode|string'
        ])->validate();
        $methode = MethodePembayaran::where('id', '=', $valid['id'])->first();
        $methode->update([
            'methode' => $valid['methode']
        ]);
        return response()->json(['message' => 'succes update']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $operasional = MethodePembayaran::where('id', '=', $id)->first();
        $operasional->delete();
        return response()->json(['message' => 'ok']);
    }
}

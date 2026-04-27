<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Operasional;
use App\Models\Purchase;
use App\Models\Saler;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RekapitulasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pembelian = Purchase::all()->groupBy(function ($item) {
            return Carbon::parse($item->created_at)->format('Y-m-d');
        });
        $penjualan = Saler::all()->groupBy(function ($item) {
            return Carbon::parse($item->created_at)->format('Y-m-d');
        });
        $operasional = Operasional::all()->groupBy(function ($item) {
            return Carbon::parse($item->created_at)->format('Y-m-d');
        });
        $tanggalSemua = collect()
            ->merge($pembelian->keys())
            ->merge($penjualan->keys())
            ->merge($operasional->keys())
            ->unique()
            ->sort()
            ->values();
        $hasil = $tanggalSemua->map(function ($tanggal) use ($pembelian, $penjualan, $operasional) {
            $totaltonasebeli = isset($pembelian[$tanggal])
                ? $pembelian[$tanggal]->sum('tonase')
                : 0;
            $totalhargabeli = isset($pembelian[$tanggal])
                ? $pembelian[$tanggal]->sum('harga')
                : 0;
            $totaljumlahbeli = isset($pembelian[$tanggal])
                ? $pembelian[$tanggal]->sum('jumlah')
                : 0;

            $totaljumlahjual = isset($penjualan[$tanggal])
                ? $penjualan[$tanggal]->sum('jumlah')
                : 0;
            $totaltonasejual = isset($penjualan[$tanggal])
                ? $penjualan[$tanggal]->sum('tonase')
                : 0;
            $totalhargajual = isset($penjualan[$tanggal])
                ? $penjualan[$tanggal]->sum('harga')
                : 0;

            $totalOperasional = isset($operasional[$tanggal])
                ? $operasional[$tanggal]->sum('jumlah')
                : 0;

            return [
                'tanggal' => $tanggal,
                'tonase_beli' => $totaltonasebeli,
                'jumlah_beli' => $totaljumlahbeli,
                'harga_beli' => $totalhargabeli,
                'tonase_jual' => $totaltonasejual,
                'jumlah_jual' => $totaljumlahjual,
                'harga_jual' => $totalhargajual,
                'operasional' => $totalOperasional,
                'laba' => 0,
            ];
        });

        return response()->json($hasil);
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

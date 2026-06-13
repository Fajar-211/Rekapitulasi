<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Saler;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //return response()->json(['request' => $request->all()]);
        $valid = Validator::make($request->all(), [
            'pembelian.tanggal' => 'nullable|date',
            'pembelian.vendor' => 'required|exists:vendors,id',
            'pembelian.driver' => 'required|exists:drivers,id',
            'pembelian.size' => 'nullable|numeric',
            'pembelian.tonase' => 'required|numeric',
            'pembelian.harga' => 'required|numeric',
            'pembelian.status' => 'required|exists:statuses,id',
            'pembelian.pembayaran' => 'required|exists:methode_pembayarans,id',
            'pembelian.mati' => 'nullable|numeric',
            'pembelian.kompensasi' => 'nullable|numeric',

            'penjualan.tanggal' => 'nullable|date',
            'penjualan.customer' => 'required|exists:customers,id',
            'penjualan.tonase' => 'required|numeric',
            'penjualan.tonasegp' => 'nullable|numeric',
            'penjualan.harga' => 'required|numeric',
            'penjualan.hargagp' => 'nullable|numeric',
            'penjualan.kasbon' => 'nullable|numeric',
            'penjualan.bongkar' => 'nullable|numeric',
            'penjualan.mati' => 'nullable|numeric',
            'penjualan.titip' => 'nullable|array',
            'penjualan.titip.*.tanggal' => 'nullable|date',
            'penjualan.titip.*.nominal' => 'required|numeric',
        ], [
            'required' => ':attribute wajib diisi'
        ])->validate();

        DB::beginTransaction();
        try {
            $pembelian = Purchase::create([
                'tanggal' => $valid['pembelian']['tanggal'],
                'vendor_id' => $valid['pembelian']['vendor'],
                'driver_id' => $valid['pembelian']['driver'],
                'size' => $valid['pembelian']['size'],
                'tonase' => $valid['pembelian']['tonase'],
                'harga' => $valid['pembelian']['harga'],
                'status_id' => $valid['pembelian']['status'],
                'methode_id' => $valid['pembelian']['pembayaran'],
                'mati' => $valid['pembelian']['mati'],
                'kompensasi' => $valid['pembelian']['kompensasi'],
                'jumlah' => ($valid['pembelian']['harga'] * $valid['pembelian']['tonase'])
            ]);

            $jumlah = (($valid['penjualan']['tonase'] - $valid['penjualan']['mati'] ?? 0) * $valid['penjualan']['harga']) + ($valid['penjualan']['tonasegp'] * $valid['penjualan']['hargagp']);
            $titip = 0;
            if (!empty($valid['penjualan']['titip'])) {
                $titip = count($valid['penjualan']['titip']);
            }
            $piutang = $jumlah - ($valid['penjualan']['kasbon'] ?? 0) - ($valid['penjualan']['kasbon'] ?? 0) - $titip;
            $penjualan = Saler::create([
                'tanggal' => $valid['penjualan']['tanggal'],
                'customer_id' => $valid['penjualan']['customer'],
                'tonase' => $valid['penjualan']['tonase'],
                'tonase_gp' => $valid['penjualan']['tonasegp'],
                'harga' => $valid['penjualan']['harga'],
                'harga_gp' => $valid['penjualan']['hargagp'],
                'kasbon' => $valid['penjualan']['kasbon'],
                'bongkar' => $valid['penjualan']['bongkar'],
                'mati' => $valid['penjualan']['mati'],
                'piutang' => $piutang,
                'jumlah' => $jumlah
            ]);
            if (!empty($valid['penjualan']['titip'])) {
                foreach ($valid['penjualan']['titip'] as $titip) {
                    $penjualan->titip()->create([
                        'tanggal' => $titip['tanggal'],
                        'nominal' => $titip['nominal']
                    ]);
                }
            }
            $date = explode(':', $valid['pembelian']['tanggal']);
            $clear = explode('-', $date[0]);
            $transaksi = '';
            for ($i = 0; $i < 3; $i++) {
                $transaksi = $transaksi . $clear[$i];
            }
            Transaksi::create([
                'transaksi' => $transaksi . rand(1, 1000),
                'purchase_id' => $pembelian->id,
                'saler_id' => $penjualan->id
            ]);
            DB::commit();
            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error([
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
            return response()->json(['message' => $e->getMessage()], 500);
        }
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

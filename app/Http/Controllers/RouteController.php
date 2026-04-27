<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function penjualan()
    {
        return Inertia::render('Transaksi/Penjualan');
    }
    public function pembelian()
    {
        return Inertia::render('Transaksi/Pembelian');
    }
    public function operasional()
    {
        return Inertia::render('Transaksi/Operasional');
    }

    public function rekapitulasi()
    {
        return Inertia::render('Rekapitulasi/Rekapitulasi');
    }

    public function customer()
    {
        return Inertia::render('Master/Customer');
    }
    public function vendor()
    {
        return Inertia::render('Master/Vendor');
    }
    public function driver()
    {
        return Inertia::render('Master/Driver');
    }
    public function pembayaran()
    {
        return Inertia::render('Master/Pembayaran');
    }
}

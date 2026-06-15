<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Saler;
use App\Models\Status;
use App\Models\Titip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function PHPSTORM_META\map;

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
            $query->whereHas('customer', function ($query) use ($cari) {
                $query->where('nama', 'like', '%' . $cari . '%');
            });
        }
        $saler = $query->with(['customer', 'titip'])->paginate(15)->withQueryString();
        $customer = Customer::all();
        $saler->getCollection()->transform(function ($s) {
            return [
                'id' => $s->id,
                'tanggal' => $s->created_at,
                'tonase' => ($s->tonase + $s->tonase_gp) - $s->mati,
                'customer' => $s->customer,
                'harga' => $s->harga,
                'jumlah' => $s->jumlah,
                'kasbon' => $s->kasbon,
                'bongkar' => $s->bongkar,
                'titip' => $s->titip->sum('nominal'),
                'piutang' => $s->piutang - $s->titip->sum('nominal')
            ];
        });
        return response()->json(['salers' => $saler, 'customers' => $customer]);
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
            'mati' => 'nullable|decimal:0,5',
            'titip' => 'array|nullable',
            'titip.*.nominal' => 'required|decimal:0,5|min:1'
        ])->validate();
        $tonase = $valid['tonase'] ?? 0;
        $tonase_gp = $valid['tonasegp'] ?? 0;
        $harga = $valid['harga'] ?? 0;
        $harga_gp = $valid['hargagp'] ?? 0;
        $kasbon = $valid['kasbon'] ?? 0;
        $bongkar = $valid['bongkar'] ?? 0;
        $mati = $valid['mati'] ?? 0;
        $jumlah = (($tonase - $mati) * $harga) + ($tonase_gp * $harga_gp);
        $titip = 0;
        if (!empty($valid['titip'])) {
            $titip = count($valid['titip']);
        }
        $piutang = $jumlah - $kasbon - $bongkar - $titip;
        $sel = Saler::create([
            'customer_id' => $valid['customer'],
            'status_id' => 2,
            'tonase' => $valid['tonase'],
            'tonase_gp' => $valid['tonasegp'],
            'harga' => $valid['harga'],
            'harga_gp' => $valid['hargagp'],
            'kasbon' => $valid['kasbon'],
            'bongkar' => $valid['bongkar'],
            'mati' => $valid['mati'],
            'piutang' => $piutang,
            'jumlah' => $jumlah
        ]);
        if (!empty($valid['titip'])) {
            $ke = 1;
            foreach ($valid['titip'] as $titip) {
                $sel->titip()->create([
                    'nomor' => $ke,
                    'nominal' => $titip['nominal']
                ]);
                $ke++;
            }
        }
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
            'status' => 'required|exists:statuses,id',
            'titip' => 'array|nullable',
            'titip.*.nominal' => 'required|decimal:0,5|min:1'
        ])->validate();
        if (!empty($valid['titip'])) {
            $titip = count($valid['titip']);
        }
        $tonase = $valid['tonase'] ?? 0;
        $tonase_gp = $valid['tonasegp'] ?? 0;
        $harga = $valid['harga'] ?? 0;
        $harga_gp = $valid['hargagp'] ?? 0;
        $kasbon = $valid['kasbon'] ?? 0;
        $bongkar = $valid['bongkar'] ?? 0;
        $mati = $valid['mati'] ?? 0;
        $titip = 0;
        $piutang = ((ceil($tonase) * $harga) + (ceil($tonase_gp) * $harga_gp)) - ($mati * $harga) - $kasbon - $bongkar - $titip;
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
            'piutang' => $piutang,
            'jumlah' => ($valid['tonase'] * $valid['harga']) + ($valid['tonasegp']) - $valid['kasbon'] - $valid['bongkar'] - $valid['mati']
        ]);
        if (!empty($valid['titip'])) {
            $ke = 1;
            foreach ($valid['titip'] as $titip) {
                $exis = Titip::where('saler_id', '=', $sale->id)->where('nomor', '=', $ke)->exists();
                if ($exis) {
                    $data = Titip::where('saler_id', '=', $sale->id)->where('nomor', '=', $ke)->first();
                    $data->update([
                        'nominal' => $titip['nominal']
                    ]);
                } else {
                    $sale->titip()->create([
                        'nomor' => $ke,
                        'nominal' => $titip['nominal']
                    ]);
                }
                $ke++;
            }
            Titip::where('saler_id', $sale->id)->where('nomor', '>', count($valid['titip']))->delete();
        }
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

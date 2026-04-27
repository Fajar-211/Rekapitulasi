<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Operasional;
use App\Models\TypeOperasional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OperasionalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Operasional::query();
        if ($request->search) {
            $cari = $request->search;
            $query->whereHas('type', function ($query) use ($cari) {
                $query->where('type', 'like', '%' . $cari . '%');
            });
        }
        $operasional = $query->with(['type'])->paginate(10)->withQueryString();
        $type = TypeOperasional::all();
        return response()->json(['operasionals' => $operasional, 'type' => $type]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'type' => 'required|exists:type_operasionals,id',
            'jumlah' => 'required|decimal:0,5'
        ])->validate();
        Operasional::create([
            'type_id' => $valid['type'],
            'jumlah' => $valid['jumlah']
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
        $operasional = Operasional::where('id', '=', $id)->first();
        $valid = Validator::make($request->all(), [
            'type' => 'required|exists:type_operasionals,id',
            'jumlah' => 'required|decimal:0,5'
        ])->validate();
        $operasional->update([
            'type_id' => $valid['type'],
            'jumlah' => $valid['jumlah']
        ]);
        return response()->json(['message' => 'ok']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $operasional = Operasional::where('id', '=', $id)->first();
        $operasional->delete();
        return response()->json(['message' => 'ok']);
    }
}

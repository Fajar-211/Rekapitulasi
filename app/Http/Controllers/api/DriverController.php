<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Driver::query();
        if ($request->search) {
            $query->where('nama', 'like', '%' . $request->search . '%')->orwhere('telp', 'like', '%' . $request->search . '%');
        }
        $drivers = $query->paginate(3)->withQueryString();
        return response()->json(['drivers' => $drivers]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'nama' => 'required|string',
            'telp' => 'nullable|digits_between:9,13'
        ], [
            'required' => ':attribute wajib diisi',
            'digits_between' => 'digit nomor harus 9 - 13'
        ])->validate();
        $driver = Driver::create([
            'nama' => $valid['nama'],
            'telp' => $valid['telp']
        ]);
        return response()->json(['message' => 'sukses', 'driver' => $driver]);
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
        $driver = Driver::where('id', '=', $id)->first();
        $valid = Validator::make($request->all(), [
            'nama' => 'required|string',
            'telp' => 'nullable|digits_between:9,13'
        ], [
            'required' => ':attribute wajib diisi',
            'digits_between' => 'digit nomor harus 9 - 13'
        ])->validate();
        $driver->update([
            'nama' => $valid['nama'],
            'telp' => $valid['telp']
        ]);
        return response()->json(['message' => 'sukses']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $driver = Driver::where('id', '=', $id)->first();
        $driver->delete();
        return response()->json(['message' => 'sukses']);
    }
}

<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Vendor::query();
        if ($request->search) {
            $query->where('nama', 'like', '%' . $request->search . '%')->orwhere('alamat', 'like', '%' . $request->search . '%');
        }
        $vendors = $query->paginate(3)->withQueryString();
        return response()->json(['vendors' => $vendors]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'nama' => 'required|string',
            'alamat' => 'nullable|max:255'
        ], [
            'required' => ':attribute wajib diisi',
            'max' => 'Maksimal 255 kata'
        ])->validate();
        $vendor = Vendor::create([
            'nama' => $valid['nama'],
            'alamat' => $valid['alamat']
        ]);
        return response()->json(['message' => 'sukses', 'vendor' => $vendor]);
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
        $vendor = Vendor::where('id', '=', $id)->first();
        $valid = Validator::make($request->all(), [
            'nama' => 'required|string',
            'alamat' => 'nullable|max:255'
        ], [
            'required' => ':attribute wajib diisi',
            'max' => 'Maksimal 255 kata'
        ])->validate();
        $vendor->update([
            'nama' => $valid['nama'],
            'alamat' => $valid['alamat']
        ]);
        return response()->json(['message' => 'sukses']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $vendor = Vendor::where('id', '=', $id)->first();
        $vendor->delete();
        return response()->json(['message' => 'sukses']);
    }
}

<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Customer::query();
        if ($request->search) {
            $query->where('nama_depan', 'like', '%' . $request->search . '%')->orwhere('nama_belakang', 'like', '%' . $request->search . '%');
        }
        $customers = $query->paginate(3)->withQueryString();
        return response()->json(['customers' => $customers]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'nama' => 'required|string',
            'last' => 'required|string'
        ], [
            'required' => ':attribute wajib diisi',
            'string' => 'input berupa string'
        ])->validate();
        Customer::create([
            'nama_depan' => $valid['nama'],
            'nama_belakang' => $valid['last']
        ]);
        return response()->json(['message' => 'sukses']);
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
        $customer = Customer::where('id', '=', $id)->first();
        $valid = Validator::make($request->all(), [
            'nama' => 'required|string',
            'last' => 'required|string'
        ], [
            'required' => ':attribute wajib diisi',
            'string' => 'input berupa string'
        ])->validate();
        $customer->update([
            'nama_depan' => $valid['nama'],
            'nama_belakang' => $valid['last']
        ]);
        return response()->json(['message' => 'sukses']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $customers = Customer::where('id', '=', $id)->first();
        $customers->delete();
        return response()->json(['message' => 'ok']);
    }
}

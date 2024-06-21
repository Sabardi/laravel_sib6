<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use App\Http\Resources\ProdukResource;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $produk = Produk::join('jenis_produk', 'jenis_produk_id', '=', 'jenis_produk.id')
            ->select('produk.*', 'jenis_produk.nama as jenis')
            ->get();

        return new ProdukResource(true, 'List Data produk', $produk);
    }

    public function DetailApi($id)
    {
        $produk = Produk::join('jenis_produk', 'produk.jenis_produk_id', '=', 'jenis_produk.id')
            ->select('produk.*', 'jenis_produk.nama as jenis')
            ->where('produk.id', $id)
            ->first();

        if ($produk) {
            return new ProdukResource(true, 'List Data produk', $produk);

        } else {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan',
            ], 404);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'kode' => 'required|unique:produk|max:10',
            'nama' => 'required|max:45',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|numeric',
            'min_stok' => 'required|numeric',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $produk = Produk::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'harga_jual' => $request->harga_jual,
            'harga_beli' => $request->harga_beli,
            'stok' => $request->stok,
            'min_stok' => $request->min_stok,
            'deskripsi' => $request->deskripsi,
            'foto' => $request->foto,
            'jenis_produk_id' => $request->jenis_produk_id,
            // 'updated_at' => $request->updated_at,
            // 'created_at' => $request->created_at,
        ]);

        return new ProdukResource(true, 'data berhasil',  $produk);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'kode' => 'required|unique:produk,kode,' . $produk->id . '|max:10',
            'nama' => 'required|max:45',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|numeric',
            'min_stok' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update the existing product record
        $produk->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'harga_jual' => $request->harga_jual,
            'harga_beli' => $request->harga_beli,
            'stok' => $request->stok,
            'min_stok' => $request->min_stok,
            'deskripsi' => $request->deskripsi,
            'foto' => $request->foto,
            'jenis_produk_id' => $request->jenis_produk_id,
        ]);

        return response()->json(['message' => 'Product updated successfully', 'product' => $produk], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Find the product by ID
        $produk = Produk::find($id);

        // Check if the product exists
        if (!$produk) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Delete the product
        $produk->delete();

        // Return a response indicating the product was deleted
        return response()->json(['message' => 'Product deleted successfully'], 200);
    }

}

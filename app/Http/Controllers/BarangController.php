<?php

namespace App\Http\Controllers;

use Yajra\DataTables\DataTables;
use App\Models\Barang;
use App\Models\Produk;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $barang = Barang::with('produk')->latest()->get();
        $produk = Produk::get();
        return view('admin.barang.index', compact('barang', 'produk'));
    }

    /**
     * Datatable
     *
     */
    public function data()
    {
        $barang = Barang::with('produk')->get();

        return DataTables::of($barang)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($barang) {
                return $barang->produk->nama_produk;
            })
            ->editColumn('harga_jual', function ($produk) {
                return "Rp " . number_format($produk->harga_jual);
            })
            ->editColumn('diskon', function ($produk) {
                return $produk->diskon . "%";
            })
            ->addColumn('action', function ($barang) {
                $buttons = '<button type="button" onclick="editBarang(`' . route('barang.update', $barang->id) . '`)" class="btn btn-sm btn-info mr-1" title="Edit Barang"><i class="fas fa-edit"></i></button>';
                $buttons .= '<button type="button" onclick="deleteBarang(`' . route('barang.destroy', $barang->id) . '`)" class="btn btn-sm btn-danger" title="Hapus Barang"><i class="fas fa-trash"></i></button>';
                return $buttons;
            })->rawColumns(['action'])->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'nama_barang' => 'required',
            'satuan' => 'required',
            'harga_jual' => 'required|numeric',
        ]);

        $kode_barang = Barang::buat_kode_barang();

        $request->merge(['kode_barang' => $kode_barang]);
        $request->merge(['stok' => 0]);

        Barang::create($request->all(['kode_barang', 'produk_id', 'nama_barang', 'satuan', 'harga_jual', 'diskon', 'stok']));

        return response()->json([
            'message' => 'Barang berhasil ditambahkan'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $barang = Barang::find($id);

        return response()->json($barang, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $barang = Barang::find($id);

        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'nama_barang' => 'required',
            'satuan' => 'required',
            'harga_jual' => 'required|numeric',
        ]);

        $barang->update($request->only(['produk_id', 'nama_barang', 'satuan', 'harga_jual', 'diskon']));

        return response()->json([
            'message' => 'Barang berhasil diupdate'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);

        $barang->delete();

        return response()->json([
            'message' => 'Barang berhasil dihapus'
        ], 200);
    }
}

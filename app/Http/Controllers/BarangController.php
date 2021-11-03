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
            ->editColumn('harga_jual', function ($barang) {
                return "Rp " . number_format($barang->harga_jual);
            })
            ->editColumn('diskon', function ($barang) {
                return $barang->diskon . "%";
            })
            ->editColumn('kadaluarsa', function ($barang) {
                return $barang->tgl_kadaluarsa ? date('d/m/Y', strtotime($barang->tgl_kadaluarsa)) : "-";
            })
            ->addColumn('status', function ($barang) {
                $status = $barang->tgl_kadaluarsa && strtotime($barang->tgl_kadaluarsa) < strtotime(date('Y-m-d')) ? '<span class="badge badge-warning">Kadaluarsa</span>' : '';
                $status = $barang->stok <= 0 ? '<span class="badge badge-secondary">Tidak ada stok</span>' : $status;
                return $status;
            })
            ->addColumn('jual_barang', function ($barang) {
                $checked = $barang->proses_ditarik == 0 ? ' checked' : '';
                return '<div class="custom-control custom-switch custom-switc">
                <input type="checkbox" class="toggle-jual-barang custom-control-input" data-barang-id="' . $barang->id . '" id="switchJualBarang' . $barang->id . '"' . $checked . ' onchange="updateTarikBarang(this)">
                <label class="custom-control-label" for="switchJualBarang' . $barang->id . '"></label>
              </div>';
            })
            ->addColumn('action', function ($barang) {
                $buttons = '<button type="button" onclick="editBarang(`' . route('barang.update', $barang->id) . '`)" class="btn btn-sm btn-info mr-1" title="Edit Barang"><i class="fas fa-edit"></i></button>';
                if ($barang->canDelete()) {
                    $buttons .= '<button type="button" onclick="deleteBarang(`' . route('barang.destroy', $barang->id) . '`)" class="btn btn-sm btn-danger" title="Hapus Barang"><i class="fas fa-trash"></i></button>';
                } else {
                    $buttons .= '<button type="button" class="btn btn-sm btn-secondary" title="Tidak dapat dihapus"><i class="fas fa-ban"></i></button>';
                }
                return $buttons;
            })->rawColumns(['action', 'status', 'jual_barang'])->make(true);
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
            'diskon' => 'numeric|min:0'
        ]);

        $kode_barang = Barang::buat_kode_barang();

        $request->merge(['kode_barang' => $kode_barang]);
        $request->merge(['stok' => 0]);

        Barang::create($request->all(['kode_barang', 'produk_id', 'nama_barang', 'satuan', 'harga_jual', 'diskon', 'stok', 'tgl_kadaluarsa']));

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
            'diskon' => 'numeric|min:0'
        ]);

        $barang->update($request->only(['produk_id', 'nama_barang', 'satuan', 'harga_jual', 'diskon', 'tgl_kadaluarsa']));

        return response()->json([
            'message' => 'Barang berhasil diupdate'
        ], 200);
    }

    /**
     * Update proses_ditarik
     *
     */
    public function updateTarikBarang(Barang $barang, Request $request){
        if($barang->update([
            'proses_ditarik' => (int) $request->proses_ditarik
        ])){
            return response()->json([
                'message' => 'Barang berhasil diupdate'
            ], 200);
        }

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

        if ($barang->canDelete()) {
            $barang->delete();

            return response()->json([
                'message' => 'Barang berhasil dihapus'
            ], 200);
        }

        return response()->json([
            'message' => 'Barang tidak dapat dihapus'
        ], 422);
    }
}

<?php

namespace App\Http\Controllers;

use Yajra\DataTables\DataTables;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.produk.index');
    }

    /**
     * Datatable
     *
     */
    public function data()
    {
        $produk = Produk::latest()->get();
        return DataTables::of($produk)
            ->addIndexColumn()
            ->addColumn('action', function ($produk) {
                $buttons = '<button type="button" onclick="editProduk(`' . route('produk.update', $produk->id) . '`)" class="btn btn-sm btn-info mr-1" title="Edit Produk"><i class="fas fa-edit"></i></button>';
                if ($produk->canDelete()) {
                    $buttons .= '<button type="button" onclick="deleteProduk(`' . route('produk.destroy', $produk->id) . '`)" class="btn btn-sm btn-danger" title="Hapus Produk"><i class="fas fa-trash"></i></button>';
                } else {
                    $buttons .= '<button type="button" class="btn btn-sm btn-secondary" title="Hapus Produk"><i class="fas fa-ban"></i></button>';
                }
                return $buttons;
            })
            ->rawColumns(['action'])
            ->make(true);
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
            'nama_produk' => 'required|unique:produk,nama_produk'
        ]);

        Produk::create($request->only('nama_produk'));

        return response()->json([
            'message' => 'Data berhasil disimpan'
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
        $produk = Produk::find($id);

        return response()->json($produk, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $produk = Produk::findOrFail($id);

        $request->validate([
            'nama_produk' => 'required|unique:produk,nama_produk,' . $produk->id
        ]);

        $produk->update($request->only('nama_produk'));

        return response()->json([
            'message' => 'Data berhasil diupdate'
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
        $produk = Produk::findOrFail($id);

        if ($produk->canDelete()) {
            $produk->delete();

            return response()->json([
                'message' => 'Data berhasil dihapus'
            ], 200);
        }

        return response()->json([
            'message' => 'Data tidak dapat dihapus'
        ], 422);
    }
}

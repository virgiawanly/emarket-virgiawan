<?php

namespace App\Http\Controllers;

use Yajra\DataTables\DataTables;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.pelanggan.index');
    }

    /**
     * DataTable
     *
     */
    public function data()
    {
        $pelanggan = Pelanggan::get();

        return DataTables::of($pelanggan)
            ->addIndexColumn()
            ->addColumn('action', function ($pelanggan) {
                $buttons = '<button type="button" onclick="editPelanggan(`' . route('pelanggan.update', $pelanggan->id) . '`)" class="btn btn-sm btn-info mr-1" title="Edit Pelanggan"><i class="fas fa-edit"></i></button>';
                $buttons .= '<button type="button" onclick="deletePelanggan(`' . route('pelanggan.destroy', $pelanggan->id) . '`)" class="btn btn-sm btn-danger" title="Hapus Pelanggan"><i class="fas fa-trash"></i></button>';
                return $buttons;
            })->rawColumns(['action'])->make(true);
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
            'nama' => 'required',
            'email' => 'required|email|unique:pelanggan,email',
            'no_telp' => 'required',
            'alamat' => 'required'
        ]);

        $kode_pelanggan = Pelanggan::buat_kode_pelanggan();

        $request->merge(['kode_pelanggan' => $kode_pelanggan]);

        Pelanggan::create($request->all());

        return response()->json([
            'message' => 'Pelanggan berhasil ditambahkan'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function show(Pelanggan $pelanggan)
    {
        return response()->json($pelanggan, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pelanggan $pelanggan)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:pelanggan,email,' . $pelanggan->id,
            'no_telp' => 'required',
            'alamat' => 'required'
        ]);

        $pelanggan->update($request->only('nama', 'email', 'no_telp', 'alamat'));

        return response()->json([
            'message' => 'Barang berhasil diupdate'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();

        return response()->json([
            'message' => 'Pelanggan berhasil dihapus'
        ], 200);
    }
}

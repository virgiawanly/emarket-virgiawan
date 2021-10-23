<?php

namespace App\Http\Controllers;

use App\Models\Pemasok;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PemasokController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.pemasok.index');
    }

    /**
     * DataTable
     *
     */
    public function data()
    {
        $pemasok = Pemasok::get();

        return DataTables::of($pemasok)
            ->addIndexColumn()
            ->addColumn('action', function ($pemasok) {
                $buttons = '<button type="button" onclick="editPemasok(`' . route('pemasok.update', $pemasok->id) . '`)" class="btn btn-sm btn-info mr-1" title="Edit Pemasok"><i class="fas fa-edit"></i></button>';
                if($pemasok->canDelete()){
                $buttons .= '<button type="button" onclick="deletePemasok(`' . route('pemasok.destroy', $pemasok->id) . '`)" class="btn btn-sm btn-danger" title="Hapus Pemasok"><i class="fas fa-trash"></i></button>';
            } else {
                $buttons .= '<button type="button" class="btn btn-sm btn-secondary" title="Hapus Pemasok"><i class="fas fa-ban"></i></button>';
                }
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
            'no_telp' => 'required',
            'kota' => 'required',
            'alamat' => 'required'
        ]);

        $kode_pemasok = Pemasok::buat_kode_pemasok();

        $request->merge(['kode_pemasok' => $kode_pemasok]);

        Pemasok::create($request->all());

        return response()->json([
            'message' => 'Pemasok berhasil ditambahkan'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pemasok  $pemasok
     * @return \Illuminate\Http\Response
     */
    public function show(Pemasok $pemasok)
    {
        return response()->json($pemasok, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pemasok  $pemasok
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pemasok $pemasok)
    {
        $request->validate([
            'nama' => 'required',
            'no_telp' => 'required',
            'kota' => 'required',
            'alamat' => 'required'
        ]);

        $pemasok->update($request->all());

        return response()->json([
            'message' => 'Pemasok berhasil diupdate'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pemasok  $pemasok
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pemasok $pemasok)
    {
        $pemasok->delete();

        return response()->json([
            'message' => 'Pemasok berhasil dihapus'
        ], 200);
    }
}

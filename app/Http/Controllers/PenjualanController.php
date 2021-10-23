<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.penjualan.index');
    }

    /**
     * Datatable
     *
     */
    public function data()
    {
        $penjualan = Penjualan::with('detail')->latest()->get();

        return DataTables::of($penjualan)
            ->addIndexColumn()
            ->editColumn('tgl_faktur', function ($penjualan) {
                return date('Y-m-d', strtotime($penjualan->tgl_faktur));
            })
            ->addColumn('kode_pelanggan', function($penjualan){
                return $penjualan->pelanggan ? '<span class="badge badge-success">' . $penjualan->pelanggan->kode_pelanggan . '<span>' : "";
            })
            ->addColumn('total_item', function($penjualan){
                return $penjualan->detail->reduce(function($total, $detail){
                    return $total + $detail->jumlah;
                });
            })
            ->addColumn('nama_user', function ($penjualan) {
                return $penjualan->user->name;
            })
            ->editColumn('total_harga', function ($penjualan) {
                return 'Rp ' . number_format($penjualan->total_bayar);
            })
            ->addColumn('action', function ($penjualan) {
                $buttons = '<button type="button" class="button-lihat-detail btn btn-sm btn-info mr-1" title="Lihat Detail" data-toggle="modal" data-target="#modalDetailPenjualan" data-penjualan-id="' . $penjualan->id . '" data-no-faktur="' . $penjualan->no_faktur . '"><i class="far fa-eye"></i></button>';
                return $buttons;
            })->rawColumns(['action', 'kode_pelanggan'])->make(true);
    }

    public function detail_data($id){
        $detail = Penjualan::with('detail')->find($id)->detail;

        return DataTables::of($detail)
            ->addIndexColumn()
            ->addColumn('kode_barang', function($detail){
                return $detail->barang->kode_barang;
            })
            ->addColumn('nama_barang', function($detail){
                return $detail->barang->nama_barang;
            })
            ->addColumn('jenis_produk', function($detail){
                return $detail->barang->produk->nama_produk;
            })
            ->editColumn('harga_jual', function($detail){
                return 'Rp ' . number_format($detail->harga_jual);
            })
            ->editColumn('diskon', function($detail){
                return $detail->diskon . '%';
            })
            ->editColumn('sub_total', function($detail){
                return 'Rp ' . number_format($detail->sub_total);
            })->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $barang = Barang::with('produk')->get();
        return view('admin.penjualan.penjualan-baru', [
            'barang' => $barang,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function show(Penjualan $penjualan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function edit(Penjualan $penjualan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Penjualan $penjualan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Penjualan $penjualan)
    {
        //
    }
}

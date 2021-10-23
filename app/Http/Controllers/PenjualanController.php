<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pelanggan;
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
        $penjualan = Penjualan::with(['detail', 'tampung_bayar'])->latest()->get();

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
            ->addColumn('diterima', function ($penjualan) {
                return 'Rp ' . number_format($penjualan->tampung_bayar->terima);
            })
            ->addColumn('kembali', function ($penjualan) {
                return 'Rp ' . number_format($penjualan->tampung_bayar->kembali);
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
        $pelanggan = Pelanggan::get();
        return view('admin.penjualan.penjualan_baru', [
            'barang' => $barang,
            'pelanggan' => $pelanggan
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
        $request->validate([
            'total_diterima' => 'required',
            'barang_id' => 'required|array'
        ]);

        $no_faktur = Penjualan::buat_no_faktur();
        $tgl_faktur = date('Y-m-d');
        $user_id = 1;
        $jumlah_barang = $request->jumlah;
        $diskon = $request->diskon;

        $list_barang = Barang::whereIn('id', $request->barang_id)->get();
        $total_harga = $list_barang->reduce(function($total, $barang, $index) use ($jumlah_barang, $diskon) {
            $harga_diskon = $barang->harga_jual - ($diskon[$index] / 100 * $barang->harga_jual);
            return $total + floor($harga_diskon * $jumlah_barang[$index]);
        });

        $request->validate([
            'total_diterima' => 'numeric|min:' . (int) $total_harga
        ]);

        $pelanggan_id = null;
        if($request->kode_pelanggan){
            $pelanggan = Pelanggan::where('kode_pelanggan', $request->kode_pelanggan)->first();
            $pelanggan_id = $pelanggan ? $pelanggan->id : null;
        }

        $penjualan = Penjualan::create([
            'no_faktur' => $no_faktur,
            'tgl_faktur' => $tgl_faktur,
            'total_bayar' => $total_harga,
            'pelanggan_id' => $pelanggan_id,
            'user_id' => $user_id,
        ]);

        foreach($list_barang as $index => $barang){
            $harga_diskon = $barang->harga_jual - ($diskon[$index] / 100 * $barang->harga_jual);;
            $subtotal = floor($harga_diskon * $jumlah_barang[$index]);

            // Buat detail penjualan
            $detail = $penjualan->detail()->create([
                'barang_id' => $barang->id,
                'harga_jual' => $barang->harga_jual,
                'diskon' => $diskon[$index],
                'jumlah' => $jumlah_barang[$index],
                'sub_total' => $subtotal
            ]);

            // Update stok barang
            $detail->barang()->decrement('stok', (int) $request->jumlah[$index]);
        };


        $penjualan->tampung_bayar()->create([
            'total' => $total_harga,
            'terima' => $request->total_diterima,
            'kembali' => (int) $request->total_diterima - $total_harga
        ]);

        return response()->json([
            'message' => 'Transaksi Berhasil',
            'link_cetak_faktur' => '/'
        ], 200);
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

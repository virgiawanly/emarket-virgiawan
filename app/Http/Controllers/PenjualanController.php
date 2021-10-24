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
            ->addColumn('kasir', function ($penjualan) {
                return $penjualan->user->name;
            })
            ->editColumn('total_harga', function ($penjualan) {
                return 'Rp ' . number_format($penjualan->total_bayar);
            })
            ->addColumn('diterima', function ($penjualan) {
                return  $penjualan->tampung_bayar ? 'Rp' . number_format($penjualan->tampung_bayar->terima) : "Rp 0";
            })
            ->addColumn('kembali', function ($penjualan) {
                return $penjualan->tampung_bayar ? 'Rp ' . number_format($penjualan->tampung_bayar->kembali) : "Rp 0";
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
                return $detail->diskon ? $detail->diskon . '%' : '-';
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
            'barang_id' => 'required|array',
            'jumlah' => 'required|array',
            'diskon' => 'required|array'
        ]);

        $no_faktur = Penjualan::buat_no_faktur();
        $tgl_faktur = date('Y-m-d');
        $user_id = 1;
        $arr_jumlah = $request->jumlah;
        $arr_diskon = $request->diskon;
        $arr_barang_id = $request->barang_id;

        $list_barang = Barang::whereIn('id', $arr_barang_id)->get();
        $total_bayar = $list_barang->reduce(function($total, $barang, $index) use ($arr_jumlah, $arr_diskon) {
            $diskon = (int) $arr_diskon[$index] ?? 0;
            $jumlah = (int) $arr_jumlah[$index] ?? 0;
            $harga_reguler = $barang->harga_jual * $jumlah;
            $subtotal = floor($harga_reguler - ($diskon / 100 * $harga_reguler));
            return $total + $subtotal;
        });

        $request->validate([
            'total_diterima' => 'numeric|min:' . $total_bayar
        ]);

        $pelanggan_id = null;
        if($request->kode_pelanggan){
            $pelanggan = Pelanggan::where('kode_pelanggan', $request->kode_pelanggan)->first();
            $pelanggan_id = $pelanggan ? $pelanggan->id : null;
        }

        $penjualan = Penjualan::create([
            'no_faktur' => $no_faktur,
            'tgl_faktur' => $tgl_faktur,
            'total_bayar' => $total_bayar,
            'pelanggan_id' => $pelanggan_id,
            'user_id' => $user_id,
        ]);

        foreach($list_barang as $index => $barang){
            $jumlah = (int) $arr_jumlah[$index] ?? 0;
            $diskon = (int) $arr_diskon[$index] ?? 0;

            if($jumlah !== 0){
                $harga_reguler = $barang->harga_jual * $jumlah;
                $subtotal = floor($harga_reguler - ($diskon / 100 * $harga_reguler));

                $detail = $penjualan->detail()->create([
                    'barang_id' => $barang->id,
                    'harga_jual' => $barang->harga_jual,
                    'diskon' => $diskon,
                    'jumlah' => $jumlah,
                    'sub_total' => $subtotal
                ]);

                $detail->barang()->decrement('stok', $jumlah);
            }
        };

        $penjualan->tampung_bayar()->create([
            'total' => $total_bayar,
            'terima' => (int) $request->total_diterima,
            'kembali' => (int) $request->total_diterima - $total_bayar
        ]);

        session(['penjualan_id' => $penjualan->id]);

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

    /**
     * Mencetak struk belanja.
     *
     */
    public function cetak_struk()
    {
        $penjualan = Penjualan::with('detail', 'tampung_bayar', 'detail.barang')->find(session('penjualan_id'));
        if(!$penjualan){
            abort(404);
        };
        return view('admin.penjualan.struk_belanja', [
            'penjualan' => $penjualan
        ]);
    }
}

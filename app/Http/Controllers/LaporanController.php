<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;

class LaporanController extends Controller
{
    public function pendapatan(Request $request)
    {
        $tgl_awal = ($request->has('tgl_awal') && $request->tgl_awal != "") ? $request->tgl_awal : date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tgl_akhir = ($request->has('tgl_akhir') && $request->tgl_akhir != "") ? $request->tgl_akhir : date('Y-m-d');

        return view('admin.laporan.pendapatan.index', compact('tgl_awal', 'tgl_akhir'));
    }

    public function getDataPendapatan($tgl_awal, $tgl_akhir)
    {
        $data = [];
        $rowIndex = 1;
        $total_pendapatan = 0;

        while (strtotime($tgl_awal) <= strtotime($tgl_akhir)) {
            $tanggal = $tgl_awal;
            $tgl_awal = date('Y-m-d', strtotime("+1 day", strtotime($tgl_awal)));

            $total_penjualan = Penjualan::where('tgl_faktur', 'LIKE', "%$tanggal%")->sum('total_bayar');
            $total_pembelian = Pembelian::where('tanggal_masuk', 'LIKE', "%$tanggal%")->sum('total');

            $pendapatan = $total_penjualan - $total_pembelian;
            $total_pendapatan += $pendapatan;

            $row = [];
            $row['DT_RowIndex'] = $rowIndex++;
            $row['tanggal'] = date('d M, Y', strtotime($tanggal));
            $row['penjualan'] = $total_penjualan ? "Rp " . number_format($total_penjualan) : "-";
            $row['pembelian'] = $total_pembelian ? "Rp " . number_format($total_pembelian) : "-";

            if ($pendapatan > 0) {
                $row['pendapatan'] = '<span class="text-success">' . '+Rp ' . number_format($pendapatan) . '</span>';
            } else if ($pendapatan < 0) {
                $row['pendapatan'] = '<span class="text-danger">' . '-Rp ' . number_format(abs($pendapatan)) . '</span>';
            } else {
                $row['pendapatan'] = "";
            }

            $data[] = $row;
        }

        $data[] = [
            'DT_RowIndex' => '',
            'tanggal' => '',
            'penjualan' => '',
            'pembelian' => 'Total Pendapatan',
            'pendapatan' => "Rp " . number_format($total_pendapatan),
        ];

        return $data;
    }

    public function dataPendapatan($tgl_awal, $tgl_akhir)
    {
        $data = $this->getDataPendapatan($tgl_awal, $tgl_akhir);

        return datatables()
            ->of($data)
            ->rawColumns(['pendapatan'])
            ->make(true);
    }

    public function exportPendapatanPDF($tgl_awal, $tgl_akhir)
    {
        $data = $this->getDataPendapatan($tgl_awal, $tgl_akhir);
        $pdf  = PDF::loadView('admin.laporan.pendapatan.pdf', compact('tgl_awal', 'tgl_akhir', 'data'));
        $pdf->setPaper('a4', 'potrait');

        return $pdf->stream('Laporan-pendapatan-'. date('Y-m-d-his') .'.pdf');
    }
}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Pendapatan</title>

    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>
<body>
    <h3 class="text-center">Laporan Pendapatan</h3>
    <p class="text-center">
        Tanggal {{ date('d M, Y', strtotime($tgl_awal)) }}
        s/d
        Tanggal {{ date('d M, Y', strtotime($tgl_akhir)) }}
    </p>

    <table width="100%" border="1px" style="border-collapse: collapse;" cellpadding="3">
        <thead>
            <tr>
                <th width="5%" style="text-align: left;">No</th>
                <th style="text-align: left;">Tanggal</th>
                <th style="text-align: left;">Penjualan</th>
                <th style="text-align: left;">Pembelian</th>
                <th style="text-align: left;">Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    @foreach ($row as $col)
                        <td>{!! $col !!}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

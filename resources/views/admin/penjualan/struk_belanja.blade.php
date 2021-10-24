<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nota Kecil</title>

    <?php
    $style = '
    <style>
        * {
            font-family: "consolas", sans-serif;
        }
        p {
            display: block;
            margin: 3px;
            font-size: 10pt;
        }
        table td {
            font-size: 9pt;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }

        @media print {
            @page {
                margin: 0;
                size: 75mm
    ';
    ?>
    <?php
    $style .=
        ! empty($_COOKIE['innerHeight'])
            ? $_COOKIE['innerHeight'] .'mm; }'
            : '}';
    ?>
    <?php
    $style .= '
            html, body {
                width: 70mm;
            }
            .btn-print {
                display: none;
            }
        }
    </style>
    ';
    ?>

    {!! $style !!}
</head>
<body onload="window.print()">
    <button class="btn-print" style="position: absolute; right: 1rem; top: rem;" onclick="window.print()">Print</button>
    <div class="text-center">
        <h3 style="margin-bottom: 5px;">E - Market</h3>
        <p>Cianjur, Jawa Barat</p>
    </div>
    <br>
    <div>
        <p style="float: left;">{{ date('d-m-Y') }}</p>
        <p style="float: right">{{ "Virgiawan Listiyandi" }}</p>
    </div>
    <div class="clear-both" style="clear: both;"></div>
    <p>No: {{ $penjualan->no_faktur }}</p>
    <p class="text-center">===================================</p>

    <br>
    <table width="100%" style="border: 0;">
        @foreach ($penjualan->detail as $detail)
            <tr>
                <td colspan="3">{{ $detail->barang->nama_barang }}</td>
            </tr>
            <tr>
                <td>{{ $detail->jumlah }} x {{ "Rp " . number_format($detail->harga_jual - ($detail->diskon / 100 * $detail->harga_jual)) }}</td>
                <td></td>
                <td class="text-right">{{ "Rp " . number_format($detail->sub_total) }}</td>
            </tr>
        @endforeach
    </table>
    <p class="text-center">-----------------------------------</p>

    <table width="100%" style="border: 0;">
        <tr>
            <td>Total Harga:</td>
            <td class="text-right">{{ "Rp " . number_format($penjualan->total_bayar) }}</td>
        </tr>
        <tr>
            <td>Total Item:</td>
            <td class="text-right">{{ $penjualan->detail->count() }}</td>
        </tr>
        <tr>
            <td>Total Bayar:</td>
            <td class="text-right">{{ "Rp " . number_format($penjualan->total_bayar) }}</td>
        </tr>
        <tr>
            <td>Diterima:</td>
            <td class="text-right">{{ "Rp " . number_format($penjualan->tampung_bayar->terima) }}</td>
        </tr>
        <tr>
            <td>Kembali:</td>
            <td class="text-right">{{ "Rp " . number_format($penjualan->tampung_bayar->kembali) }}</td>
        </tr>
    </table>

    <p class="text-center">===================================</p>
    <p class="text-center">-- TERIMA KASIH --</p>

    <script>
        let body = document.body;
        let html = document.documentElement;
        let height = Math.max(
                body.scrollHeight, body.offsetHeight,
                html.clientHeight, html.scrollHeight, html.offsetHeight
            );

        document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "innerHeight="+ ((height + 50) * 0.264583);
    </script>
</body>
</html>

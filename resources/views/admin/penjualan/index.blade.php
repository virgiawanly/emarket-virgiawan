@extends('admin.layouts.main')

@section('content-header')
    <h1>Daftar Penjualan</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="/">Dashboard</a></div>
        <div class="breadcrumb-item">Daftar Penjualan</div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="tablePenjualan" class="table table-striped table-sm table-bordered" style="width: 100%">
                        <thead>
                            <tr>
                                <th>
                                    #
                                </th>
                                <th>Tanggal</th>
                                <th>Kode Pelanggan</th>
                                <th>Total Item</th>
                                <th>Kasir</th>
                                <th>Total Harga</th>
                                <th>Diterima</th>
                                <th>Kembali</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('bottom')

    <!-- Modal Detail Pembelian -->
    @include('admin.penjualan.modal_detail');

@endpush

@push('head')
    <!-- DataTable -->
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css" rel="stylesheet" />
@endpush

@push('script')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.7/dist/sweetalert2.all.min.js"></script>

    <!-- DataTables -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js">
    </script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js">
    </script>

    <script>
        let table;
        let tableDetailPenjualan;

        $(function() {
            table = $('#tablePenjualan').DataTable({
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                responsive: true,
                ajax: {
                    url: '{{ route('penjualan.data') }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                    },
                    {
                        data: 'tgl_faktur'
                    },
                    {
                        data: 'kode_pelanggan'
                    },
                    {
                        data: 'total_item'
                    },
                    {
                        data: 'kasir'
                    },
                    {
                        data: 'total_harga'
                    },
                    {
                        data: 'diterima'
                    },
                    {
                        data: 'kembali'
                    },
                    {
                        data: 'action',
                        searchable: false,
                        sortable: false
                    },
                ]
            });

            tableDetailPenjualan = $('#tableDetailPenjualan').DataTable({
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                columns: [{
                        data: 'DT_RowIndex',
                    },
                    {
                        data: 'kode_barang'
                    },
                    {
                        data: 'nama_barang'
                    },
                    {
                        data: 'jenis_produk'
                    },
                    {
                        data: 'harga_jual'
                    },
                    {
                        data: 'diskon'
                    },
                    {
                        data: 'jumlah'
                    },
                    {
                        data: 'sub_total'
                    }
                ]
            });
        });

        $('#tablePenjualan').on('click', '.button-lihat-detail', function() {
            let id_penjualan = $(this).data('penjualan-id');
            let no_faktur = $(this).data('no-faktur');
            $('span.no-faktur').text(no_faktur);
            tableDetailPenjualan.ajax.url(`/penjualan/detail/data/${id_penjualan}`).load();
        })

        const toaster = Swal.mixin({
            toast: true,
            position: 'top-right',
            showConfirmButton: false,
            timer: 2500,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
    </script>

@endpush

@extends('admin.layouts.main')

@section('content-header')
    <h1>Daftar Pembelian</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="/">Dashboard</a></div>
        <div class="breadcrumb-item">Daftar Pembelian</div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a class="btn btn-success" href="{{ route('pembelian.create') }}"><i
                            class="fas fa-plus-circle mr-2"></i><span>Pembelian Baru</span></a>
                </div>
                <div class="card-body">
                    <table id="tablePembelian" class="table table-striped table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    #
                                </th>
                                <th>Kode Masuk</th>
                                <th>Pemasok</th>
                                <th>Operator</th>
                                <th>Tanggal</th>
                                <th>Jenis Barang</th>
                                <th>Total Bayar</th>
                                <th><i class="fas fa-cog"></i></th>
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
    @include('admin.pembelian.modal_detail');

@endpush

@push('script')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.7/dist/sweetalert2.all.min.js"></script>

    <!-- DataTables -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js">
    </script>

    <script>
        let table;
        let tableDetailPembelian;

        $(function() {
            table = $('#tablePembelian').DataTable({
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                responsive: true,
                autoWidth: true,
                ajax: {
                    url: '{{ route('pembelian.data') }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                    },
                    {
                        data: 'kode_masuk'
                    },
                    {
                        data: 'nama_pemasok'
                    },
                    {
                        data: 'nama_user'
                    },
                    {
                        data: 'tanggal_masuk'
                    },
                    {
                        data: 'total_barang'
                    },
                    {
                        data: 'total_bayar'
                    },
                    {
                        data: 'action',
                        searchable: false,
                        sortable: false
                    },
                ]
            });

            tableDetailPembelian = $('#tableDetailPembelian').DataTable({
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
                        data: 'harga_beli'
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

        $('#tablePembelian').on('click', '.button-lihat-detail', function() {
            let id_pembelian = $(this).data('pembelian-id');
            let kode_pembelian = $(this).data('kode-pembelian');
            $('span.kode-pembelian').text(kode_pembelian);
            tableDetailPembelian.ajax.url(`/pembelian/detail/data/${id_pembelian}`).load();
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

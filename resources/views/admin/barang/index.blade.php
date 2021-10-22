@extends('admin.layouts.main')

@section('content-header')
    <h1>Daftar Barang</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="/">Dashboard</a></div>
        <div class="breadcrumb-item">Daftar Barang</div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-success" onclick="tambahBarang('{{ route('barang.store') }}')"><i
                            class="fas fa-plus-circle mr-2"></i><span>Tambah Barang</span></button>
                </div>
                <div class="card-body">
                    <table id="barangTable" class="table table-striped table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    #
                                </th>
                                <th>Kode Barang</th>
                                <th>Jenis Produk</th>
                                <th>Nama Barang</th>
                                <th>Harga Jual</th>
                                <th>Diskon</th>
                                <th>Satuan</th>
                                <th>Stok</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('bottom')

    <!-- Modal Tambah Barang -->
    @include('admin.barang.modal_form');

@endpush

@push('head')
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-selection {
            padding-top: 6px !important;
        }
    </style>
@endpush

@push('script')

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.7/dist/sweetalert2.all.min.js"></script>

    <!-- DataTables -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js">
    </script>

    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        let table;

        $(function() {
            table = $('#barangTable').DataTable({
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                responsive: true,
                autoWidth: true,
                ajax: {
                    url: '{{ route('barang.data') }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                    },
                    {
                        data: 'kode_barang'
                    },
                    {
                        data: 'nama_produk'
                    },
                    {
                        data: 'nama_barang'
                    },
                    {
                        data: 'harga_jual'
                    },
                    {
                        data: 'diskon'
                    },
                    {
                        data: 'satuan'
                    },
                    {
                        data: 'stok'
                    },
                    {
                        data: 'action',
                        searchable: false,
                        sortable: false
                    },
                ]
            });

            $('.select-produk').select2();
        });

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

        $('#modalForm').on('submit', function(e) {
            e.preventDefault();
            clearErrors();
            let url = $('#modalForm form').attr('action');
            let formData = $('#modalForm form').serialize();
            $.post(url, formData)
                .done((res) => {
                    $('#modalForm').modal('hide');

                    table.ajax.reload();

                    toaster.fire({
                        icon: 'success',
                        title: res.message
                    });
                }).fail((err) => {
                    console.log(err.responseJSON);
                    if (err.status === 422) {
                        displayErrors(err.responseJSON.errors);
                    };

                    toaster.fire({
                        icon: 'error',
                        title: 'Data gagal disimpan'
                    });

                    return;
                })
        })

        const tambahBarang = (url) => {
            clearErrors();
            $('#modalForm').modal('show');

            $('#modalForm .modal-title').text('Tambah Produk');
            $('#modalForm .modal-submit-button').text('Tambah Produk');
            $('#modalForm form')[0].reset();
            $('#modalForm form').attr('action', url);
            $('#modalForm [name=_method]').val('post');

            $('#modalForm').on('shown.bs.modal', function() {
                $('#modalForm [name=kode_barang]').focus();
            });
        }

        const editBarang = (url) => {
            clearErrors();
            $('#modalForm').modal('show');

            $('#modalForm .modal-title').text('Edit Produk');
            $('#modalForm .modal-submit-button').text('Edit Produk');
            $('#modalForm form')[0].reset();
            $('#modalForm form').attr('action', url);
            $('#modalForm [name=_method]').val('put');

            $('#modalForm').on('shown.bs.modal', function() {
                $('#modalForm [name=nama_produk]').focus();
            });

            $.get(url)
                .done((res) => {
                    $('#modalForm [name=produk_id]').val(res.produk_id);
                    $('#modalForm [name=nama_barang]').val(res.nama_barang);
                    $('#modalForm [name=kode_barang]').val(res.kode_barang);
                    $('#modalForm [name=harga_jual]').val(res.harga_jual);
                    $('#modalForm [name=diskon]').val(res.diskon);
                    $('#modalForm [name=satuan]').val(res.satuan);
                    $('#modalForm [name=stok]').val(res.stok);
                })
                .fail((errors) => {
                    alert('Tidak dapat menampilkan data');
                    return;
                });
        }

        const deleteBarang = (url) => {
            Swal.fire({
                title: 'Hapus Barang',
                text: "Anda yakin ingin menghapus barang ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#bbb',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(url, {
                            '_token': $('[name=_token]').val(),
                            '_method': 'delete'
                        })
                        .done((res) => {
                            toaster.fire({
                                icon: 'success',
                                title: res.message
                            });

                            table.ajax.reload();
                        })
                        .fail((err) => {
                            // Trigger toast
                            toaster.fire({
                                icon: 'error',
                                title: 'Data gagal dihapus'
                            });
                            return;
                        });
                }
            })
        }

        const displayErrors = (errors) => {
            clearErrors();
            for (const key in errors) {
                let parent = $(`#modalForm [name=${key}]`).parent();
                if (!parent.find('span.form-errors').length > 0) {
                    parent.append(`<span class="form-errors text-danger"></span>`);
                }
                parent.find('span.form-errors').text(errors[key][0]);
            }
        }

        const clearErrors = () => {
            $('span.form-errors').text('');
        }
    </script>

@endpush

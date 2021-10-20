@extends('admin.layouts.main')

@section('content-header')
    <h1>Pelanggan</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="/">Dashboard</a></div>
        <div class="breadcrumb-item">Pelanggan</div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-success" onclick="tambahPelanggan('{{ route('pelanggan.store') }}')"><i
                            class="fas fa-plus-circle mr-2"></i><span>Tambah Pelanggan</span></button>
                </div>
                <div class="card-body">
                    <table id="pelangganTable" class="table table-striped table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    #
                                </th>
                                <th>Kode Pelanggan</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No Telepon</th>
                                <th>Alamat</th>
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
    @include('admin.pelanggan.modal_form');

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

        $(function() {
            table = $('#pelangganTable').DataTable({
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                responsive: true,
                autoWidth: true,
                ajax: {
                    url: '{{ route('pelanggan.data') }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                    },
                    {
                        data: 'kode_pelanggan'
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'no_telp'
                    },
                    {
                        data: 'alamat'
                    },
                    {
                        data: 'action',
                        searchable: false,
                        sortable: false
                    },
                ]
            });
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

        const tambahPelanggan = (url) => {
            clearErrors();
            $('#modalForm').modal('show');

            $('#modalForm .modal-title').text('Tambah Pelanggan');
            $('#modalForm .modal-submit-button').text('Tambah Pelanggan');
            $('#modalForm form')[0].reset();
            $('#modalForm form').attr('action', url);
            $('#modalForm [name=_method]').val('post');

            $('#modalForm').on('shown.bs.modal', function() {
                $('#modalForm [name=kode_barang]').focus();
            });
        }

        const editPelanggan = (url) => {
            clearErrors();
            $('#modalForm').modal('show');

            $('#modalForm .modal-title').text('Edit Pelanggan');
            $('#modalForm .modal-submit-button').text('Edit Pelanggan');
            $('#modalForm form')[0].reset();
            $('#modalForm form').attr('action', url);
            $('#modalForm [name=_method]').val('put');

            $('#modalForm').on('shown.bs.modal', function() {
                $('#modalForm [name=nama]').focus();
            });

            $.get(url)
                .done((res) => {
                    $('#modalForm [name=nama]').val(res.nama);
                    $('#modalForm [name=email]').val(res.email);
                    $('#modalForm [name=no_telp]').val(res.no_telp);
                    $('#modalForm [name=alamat]').val(res.alamat);
                })
                .fail((errors) => {
                    alert('Tidak dapat menampilkan data');
                    return;
                });
        }

        const deletePelanggan = (url) => {
            Swal.fire({
                title: 'Hapus Pelanggan',
                text: "Anda yakin ingin menghapus pelanggan ini?",
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

@extends('admin.layouts.main')

@section('content-header')
    <h1>Users</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="/">Dashboard</a></div>
        <div class="breadcrumb-item">Users</div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-success" onclick="registerUser('{{ route('users.store') }}')"><i
                            class="fas fa-plus-circle mr-2"></i><span>Tambah User</span></button>
                </div>
                <div class="card-body">
                    <table id="usersTable" class="table table table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    #
                                </th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
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

    @include('admin.users.modal_form')

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

        $(function() {
            table = $('#usersTable').DataTable({
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                responsive: true,
                ajax: {
                    url: '{{ route('user.all_user') }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                    },
                    {
                        data: 'photo',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'role'
                    },
                    {
                        data: 'action',
                        searchable: false,
                        sortable: false
                    },
                ]
            });
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

        const registerUser = (url) => {
            $('#modalForm').modal('show');

            $('#modalForm .modal-title').text('Register User');
            $('#modalForm .modal-submit-button').text('Simpan');
            $('#modalForm form')[0].reset();
            $('#modalForm form').attr('action', url);
            $('#modalForm [name=_method]').val('post');

            $('#modalForm').on('shown.bs.modal', function() {
                $('#modalForm [name=name]').focus();
            });
        }

        const editUser = (url) => {
            clearErrors();
            $('#modalForm').modal('show');

            $('#modalForm .modal-title').text('Edit User');
            $('#modalForm .modal-submit-button').text('Edit User');
            $('#modalForm form')[0].reset();
            $('#modalForm form').attr('action', url);
            $('#modalForm [name=_method]').val('put');

            $('#modalForm').on('shown.bs.modal', function() {
                $('#modalForm [name=name]').focus();
            });

            $.get(url)
                .done((res) => {
                    $('#modalForm [name=name]').val(res.name);
                    $('#modalForm [name=email]').val(res.email);
                    $(`#modalForm [name=level][value=${res.level}]`).prop('checked', true);
                })
                .fail((errors) => {
                    alert('Tidak dapat menampilkan data');
                    return;
                });
        }

        const deleteUser = (url) => {
            Swal.fire({
                title: 'Hapus User',
                text: "Anda yakin ingin menghapus user ini?",
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

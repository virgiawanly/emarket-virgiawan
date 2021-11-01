@extends('admin.layouts.main')

@section('content-header')
    <h1>Pembelian Barang</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="/">Dashboard</a></div>
        <div class="breadcrumb-item active"><a href="/pembelian">Pembelian</a></div>
        <div class="breadcrumb-item">Pembelian Baru</div>
    </div>
@endsection

@section('content')
    <form action="{{ route('pembelian.store') }}" id="formPembelian" method="POST">
        @csrf
        <input type="hidden" name="pemasok_id">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <label for="namaPemasok">Pemasok</label>
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <input type="text" disabled id="namaPemasok" placeholder="-"
                                                class="form-control">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" data-toggle="modal"
                                                    data-target="#modalPilihPemasok"><i class="fas fa-arrow-right mr-1"></i>
                                                    Pilih</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <label for="tglMasuk">Tanggal</label>
                                    </div>
                                    <div class="col-sm-10">
                                        <input type="date" id="tglMasuk" name="tanggal_masuk" class="form-control"
                                            value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <table class="info-pemasok table table-sm table-borderless">
                                    <tr>
                                        <th style="width: 200px">Nama Pemasok</th>
                                        <th style="width: 10px">:</th>
                                        <td>-</td>
                                    </tr>
                                    <tr>
                                        <th>No. Telp</th>
                                        <th style="width: 10px">:</th>
                                        <td>-</td>
                                    </tr>
                                    <tr>
                                        <th>Alamat</th>
                                        <th style="width: 10px">:</th>
                                        <td>-</td>
                                    </tr>
                                    <tr>
                                        <th>Kota</th>
                                        <th style="width: 10px">:</th>
                                        <td>-</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row mt-4">
                            <div class="col-md-2">
                                <label for="cariBarang">Kode Barang</label>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control cari-barang" id="cariBarang">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-primary px-3" data-toggle="modal"
                                            data-target="#modalPilihBarang"><i class="fas fa-arrow-right"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <table id="tableListBarang" class="table table-sm table-bordered">
                            <thead>
                                <th>#</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Harga Beli</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th><i class="fas fa-cog"></i></th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                        <div class="row mt-2">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-body bg-dark text-white text-center">
                                        <h4 class="display-4 total-rupiah" style="font-family: consolas, sans-serif;">Rp. 0
                                        </h4>
                                    </div>
                                    <div class="card-footer" style="background-color: #fcfcfc">
                                        <span class="total-terbilang">Lima Puluh Juta Rupiah</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="totalBayar" class="label">Total</label>
                                    <input type="text" readonly id="totalBayar" placeholder="Rp " class="form-control">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <div class="text-right">
                            <button class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Pembelian</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('bottom')

    <!-- Modal Pilih Pemasok -->
    @include('admin.pembelian.modal_pemasok');

    <!-- Modal pilih Barang -->
    @include('admin.pembelian.modal_barang');

@endpush

@push('script')

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.7/dist/sweetalert2.all.min.js"></script>

    <!-- DataTables -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js">
    </script>

    <!-- Fungsi Terbilang -->
    <script src="{{ asset('js/terbilang.js') }}"></script>

    <script>
        let tableListBarang;

        const formatter = new Intl.NumberFormat('id-ID');

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

        // Fungsi untuk mengupdate subtotal dari tiap barang
        const updateSubTotal = function() {
            setTimeout(() => {
                let harga_beli = parseInt($(this).closest('tr').find('input[name="harga_beli[]"]').val() || 0);
                let jumlah = parseInt($(this).closest('tr').find('input[name="jumlah[]"]').val() || 0);
                let subtotal = $(this).closest('tr').find('.display-subtotal');
                subtotal.text(formatter.format(harga_beli * jumlah));
                updateTotalHarga();
            });
        }

        // Fungsi untuk mengupdate total harga
        const updateTotalHarga = function() {
            setTimeout(() => {
                let harga_beli = $('input[name="harga_beli[]"]').map((i, input) => parseInt($(input).val() ||
                        0))
                    .get();
                let jumlah = $('input[name="jumlah[]"]').map((i, input) => parseInt($(input).val() || 0)).get();
                let subtotal = harga_beli.map((harga, i) => harga * jumlah[i]);
                let total = subtotal.reduce((acc, curr) => acc + curr, 0);

                $('.total-rupiah').text('Rp ' + formatter.format(total));
                $('input#totalBayar').val('Rp ' + formatter.format(total));
                $('.total-terbilang').text(terbilang(total));
            })
        }

        $(function() {
            $('#tablePilihBarang').DataTable({
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                responsive: true,
                autoWidth: false,
            });

            $('#tablePilihPemasok').DataTable({
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                responsive: true,
                autoWidth: false,
            });

            tableListBarang = $('#tableListBarang').DataTable({
                paging: false,
                lengthChange: false,
                ordering: false,
                autoWidth: true,
                searching: false,
                info: false,
            });
        });

        // Event ketika button pilih barang di klik
        $('#tablePilihBarang').on('click', '.button-pilih-barang', function() {
            let tbListBarang = $('#tableListBarang');
            let barang_id = $(this).data('barang-id');

            let arr_barang_id = tbListBarang.find('tbody tr').map(function(i, row) {
                return parseInt($(row).find('input[name="barang_id[]"]').eq(0).val()) || null;
            }).get();

            // Cek apakah sudah ada barang dengan id yang sama
            if (arr_barang_id.some((id) => barang_id == id)) {
                let input_jumlah = $(`input[name="barang_id[]"][value="${barang_id}"]`).closest('tr').find(
                    'input[name="jumlah[]"]');

                input_jumlah.val(function() {
                    return parseInt($(this).val() || 0) + 1;
                });

                input_jumlah.trigger('change');
            }
            // Jika tidak, buat row baru
            else {
                let row = $(this).closest('tr');
                let id_kode_barang =
                    `<span class="kode-barang">${row.find('td').eq(2).text()}</span><input type="hidden" name="barang_id[]" value="${barang_id}">`;
                let nama_barang = row.find('td').eq(3).text();
                let harga_barang = row.find('td').eq(4).text();
                let inputHarga =
                    `<input type="number" class="form-control" name="harga_beli[]" value="${harga_barang}" min="1">`;
                let inputJumlah = `<input type="number" class="form-control" name="jumlah[]" value="1" min="1">`;
                let subtotal = `Rp <span class="display-subtotal">${formatter.format(harga_barang)}</span>`;
                let buttonHapus =
                    `<button type="button" class="button-hapus-barang btn btn-sm btn-danger" title="Hapus Barang"><i class="fas fa-trash"></i></button>`;
                let rowNumber = arr_barang_id.length + 1;

                tableListBarang.row.add([
                    rowNumber, id_kode_barang, nama_barang, inputHarga, inputJumlah, subtotal, buttonHapus
                ]).draw();
            }

            updateTotalHarga();
            $('#modalPilihBarang').modal('hide');
        });

        // Event ketika button pilih pemasok di klik : update info pemasok
        $('#tablePilihPemasok').on('click', '.button-pilih-pemasok', function() {
            let row = $(this).closest('tr');
            let nama = row.find('td').eq(1).text();
            let no_telp = row.find('td').eq(2).text();
            let alamat = row.find('td').eq(3).text();
            let kota = row.find('td').eq(4).text();

            let info = $('table.info-pemasok');
            info.find('tr').eq(0).find('td').text(nama);
            info.find('tr').eq(1).find('td').text(no_telp);
            info.find('tr').eq(2).find('td').text(alamat);
            info.find('tr').eq(3).find('td').text(kota);

            let pemasok_id = $(this).data('pemasok-id');
            $('input[name="pemasok_id"]').val(pemasok_id);
            $('input#namaPemasok').val(nama);

            $('#modalPilihPemasok').modal('hide');
        });

        // Event ketika menghapus barang dari list
        $('#tableListBarang').on('click', '.button-hapus-barang', function() {
            tableListBarang.row($(this).parents('tr')).remove().draw();
            updateTotalHarga();
        });

        // Event ketika input jumlah diubah : update sub total
        $('#tableListBarang').on('keydown change', 'input[name="jumlah[]"]', updateSubTotal);

        // Event ketika input harga beli diubah : update sub total
        $('#tableListBarang').on('keydown change', 'input[name="harga_beli[]"]', updateSubTotal);

        // Event ketika input cari barang diubah : buka modal
        $('input.cari-barang').on('keydown', function() {
            event.preventDefault();
            $('#modalPilihBarang').modal('show');
            $(this).val('');
        });

        // Event ketika form di submit
        $('form#formPembelian').on('submit', function() {
            event.preventDefault();
            let url = $(this).attr('action');
            let formData = $(this).serialize();
            $.post(url, formData)
                .done((res) => {
                    Swal.fire({
                        icon: 'success',
                        title: res.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        location.reload();
                    });
                }).fail((err) => {
                    console.log(err.responseJSON);
                    toaster.fire({
                        icon: 'error',
                        title: 'Data gagal disimpan'
                    });

                    return;
                })
        })
    </script>
@endpush

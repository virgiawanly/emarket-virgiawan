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
    <form action="{{ route('penjualan.store') }}" id="formPenjualan" method="POST">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body pb-0">
                        <div class="form-group row">
                            <div class="col-md-2">
                                <label for="cariBarang">Kode Barang</label>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control cari-barang" id="cariBarang" />
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-primary px-3" data-toggle="modal"
                                            data-target="#modalPilihBarang">
                                            <i class="fas fa-arrow-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <table id="tableListBarang" class="table table-sm table-bordered">
                            <thead>
                                <th>#</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Harga Jual</th>
                                <th>Jumlah</th>
                                <th>Diskon %</th>
                                <th>Subtotal</th>
                                <th><i class="fas fa-cog"></i></th>
                            </thead>
                            <tbody></tbody>
                        </table>

                        <div class="row mt-2">
                            <div class="col-md-8">
                                <div class="card">
                                    <div
                                        class="
                                        card-body
                                        bg-dark
                                        text-white text-center
                                    ">
                                        <h4 class="display-4 total-rupiah" style="font-family: consolas, sans-serif;">
                                            Rp. 0
                                        </h4>
                                    </div>
                                    <div class="card-footer" style="background-color: #fcfcfc">
                                        <span class="total-terbilang">Lima Puluh Juta Rupiah</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row mb-3">
                                    <div class="col-sm-2">
                                        <label for="totalHarga" class="label font-weight-bold">Total</label>
                                    </div>
                                    <div class="col-sm-10">
                                        <input type="text" disabled id="totalHarga" placeholder="Rp "
                                            class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <div class="col-sm-2">
                                        <label for="cariPelanggan" class="label font-weight-bold">Kode Member</label>
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <input type="text" class="form-control cari-pelanggan" name="kode_pelanggan"
                                                id="cariPelanggan" />
                                            <div class="input-group-prepend">
                                                <button type="button" class="btn btn-primary px-3" data-toggle="modal"
                                                    data-target="#modalPilihPelanggan">
                                                    <i class="fas fa-arrow-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <div class="col-sm-2">
                                        <label for="totalBayar" class="label font-weight-bold">Diterima</label>
                                    </div>
                                    <div class="col-sm-10">
                                        <input type="number" id="totalBayar" name="total_diterima" placeholder="Rp "
                                            class="form-control" min="0" />
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <div class="col-sm-2">
                                        <label for="totalKembali" class="label font-weight-bold">Kembali</label>
                                    </div>
                                    <div class="col-sm-10">
                                        <input type="text" disabled id="totalKembali" placeholder="Rp "
                                            class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-right">
                            <button class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Simpan Pembelian
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('bottom')

    <!-- Modal pilih Barang -->
    @include('admin.penjualan.modal_barang');
    <!-- Modal pilih Pelanggan -->
    @include('admin.penjualan.modal_pelanggan');

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

        const formatter = new Intl.NumberFormat("id-ID");

        const toaster = Swal.mixin({
            toast: true,
            position: "top-right",
            showConfirmButton: false,
            timer: 2500,
            didOpen: (toast) => {
                toast.addEventListener("mouseenter", Swal.stopTimer);
                toast.addEventListener("mouseleave", Swal.resumeTimer);
            },
        });

        // Fungsi untuk mengupdate subtotal dari tiap barang
        const updateSubTotal = function() {
            setTimeout(() => {
                let diskon = parseInt(
                    $(this).closest("tr").find('input[name="diskon[]"]').val() || 0
                );
                if (diskon > 100) {
                    diskon = 100;
                    $(this).closest("tr").find('input[name="diskon[]"]').val(100);
                }
                let harga_jual = parseInt(
                    $(this).closest("tr").find('input[name="harga_jual[]"]').val() || 0
                );
                let jumlah = parseInt(
                    $(this).closest("tr").find('input[name="jumlah[]"]').val() || 0
                );
                let harga_diskon = harga_jual - (diskon / 100 * harga_jual);
                let subtotal = Math.round(harga_diskon * jumlah);
                $(this)
                    .closest("tr")
                    .find(".display-subtotal")
                    .text(formatter.format(subtotal));
                hitungKembalian();
                updateTotalHarga();
            });
        }

        // Fungsi untuk mengupdate total harga
        const updateTotalHarga = function() {
            setTimeout(() => {
                let harga_jual = $('input[name="harga_jual[]"]')
                    .map((i, input) => parseInt($(input).val() || 0))
                    .get();
                let diskon = $('input[name="diskon[]"]')
                    .map((i, input) => parseInt($(input).val() || 0))
                    .get();
                let jumlah = $('input[name="jumlah[]"]')
                    .map((i, input) => parseInt($(input).val() || 0))
                    .get();
                let subtotal = harga_jual.map((harga, i) => {
                    let harga_diskon = harga - (diskon[i] / 100 * harga);
                    return Math.floor(harga_diskon * jumlah[i]);
                });
                let total = subtotal.reduce((acc, curr) => acc + curr, 0);

                $(".total-rupiah").text("Rp " + formatter.format(total));
                $("input#totalHarga").val("Rp " + formatter.format(total));
                $(".total-terbilang").text(terbilang(total));
            });
        }

        // Fungsi untuk menghitung kembalian
        const hitungKembalian = function() {
            setTimeout(() => {
                let harga_jual = $('input[name="harga_jual[]"]')
                    .map((i, input) => parseInt($(input).val() || 0))
                    .get();
                let diskon = $('input[name="diskon[]"]')
                    .map((i, input) => parseInt($(input).val() || 0))
                    .get();
                let jumlah = $('input[name="jumlah[]"]')
                    .map((i, input) => parseInt($(input).val() || 0))
                    .get();
                let subtotal = harga_jual.map((harga, i) => {
                    let harga_diskon = harga - (diskon[i] / 100 * harga);
                    return Math.floor(harga_diskon * jumlah[i]);
                });
                let total = subtotal.reduce((acc, curr) => acc + curr, 0);
                let total_diterima = parseInt($('input[name="total_diterima"]').val() || 0);
                let kembalian = total_diterima - total;

                $(".total-rupiah").text("Rp " + formatter.format(total));
                $("input#totalKembali").val("Rp " + formatter.format(kembalian > 0 ? kembalian : 0));
            });
        }

        $(function() {
            $("#tablePilihBarang").DataTable({
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                responsive: true,
                autoWidth: false,
            });

            $('#tablePilihPelanggan').DataTable({
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                responsive: true,
                autoWidth: false,
            });

            tableListBarang = $("#tableListBarang").DataTable({
                paging: false,
                lengthChange: false,
                ordering: false,
                autoWidth: true,
                searching: false,
                info: false,
            });
        });

        // Event ketika button pilih barang di klik
        $("#tablePilihBarang").on("click", ".button-pilih-barang", function() {
            let tbListBarang = $("#tableListBarang");
            let barang_id = $(this).data("barang-id");

            let arr_barang_id = tbListBarang
                .find("tbody tr")
                .map(function(i, row) {
                    return (
                        parseInt(
                            $(row).find('input[name="barang_id[]"]').eq(0).val()
                        ) || null
                    );
                })
                .get();

            // Cek apakah sudah ada barang dengan id yang sama
            if (arr_barang_id.some((id) => barang_id == id)) {
                // Jika Ya, update jumlah barang
                let input_jumlah = $(
                        `input[name="barang_id[]"][value="${barang_id}"]`
                    )
                    .closest("tr")
                    .find('input[name="jumlah[]"]');

                input_jumlah.val(function() {
                    return parseInt($(this).val()) + 1;
                });

                input_jumlah.trigger("change");
            } else {
                // Jika Tidak, buat row baru
                let row = $(this).closest("tr");
                let id_kode_barang = `<span class="kode-barang">${row.find("td").eq(2).text()}</span>
                <input type="hidden" name="barang_id[]" value="${barang_id}">`;
                let nama_barang = row.find("td").eq(3).text();
                let harga_jual = parseInt(row.find("td").eq(4).text());
                let diskon = parseInt(row.find("td").eq(5).text());
                let harga_diskon = Math.round(harga_jual - (diskon / 100 * harga_jual));

                let displayHarga = `Rp <span class="display-harga">${formatter.format(harga_jual)}</span>
                <input type="hidden" disabled class="form-control" name="harga_jual[]" value="${harga_jual}">`;
                let inputJumlah = `<input type="number" class="form-control" name="jumlah[]" value="1" min="1">`;
                let inputDiskon =
                    `<input type="number" class="form-control" name="diskon[]" value="${diskon}" min="0" max="100">`;
                let displaySubtotal = `Rp <span class="display-subtotal">${formatter.format(harga_diskon)}</span>`;
                let buttonHapus =
                    `<button type="button" class="button-hapus-barang btn btn-sm btn-danger" title="Hapus Barang"><i class="fas fa-trash"></i></button>`;
                let rowNumber = arr_barang_id.length + 1;

                tableListBarang.row
                    .add([
                        rowNumber,
                        id_kode_barang,
                        nama_barang,
                        displayHarga,
                        inputJumlah,
                        inputDiskon,
                        displaySubtotal,
                        buttonHapus,
                    ])
                    .draw();
            }

            updateTotalHarga();
            $("#modalPilihBarang").modal("hide");
        });

        // Event ketika button pilih pelanggan di klik : update input kode pelanggan
        $("#tablePilihPelanggan").on("click", ".button-pilih-pelanggan", function() {
            let kode_pelanggan = $(this).data('kode-pelanggan');
            $('input[name="kode_pelanggan"]').val(kode_pelanggan);
            $("#modalPilihPelanggan").modal("hide");
        });

        // Event ketika menghapus barang dari list
        $("#tableListBarang").on("click", ".button-hapus-barang", function() {
            tableListBarang.row($(this).parents("tr")).remove().draw();
            updateTotalHarga();
        });

        // Event ketika input jumlah diubah : update sub total
        $("#tableListBarang").on(
            "keydown change",
            'input[name="jumlah[]"]',
            updateSubTotal
        );

        // Event ketika input harga jual diubah : update sub total
        $("#tableListBarang").on(
            "keydown change",
            'input[name="harga_jual[]"]',
            updateSubTotal
        );

        // Event ketika input diskon diubah : update sub total
        $("#tableListBarang").on(
            "keydown change",
            'input[name="diskon[]"]',
            updateSubTotal
        );
        // Event ketika input total uang diterima diubah : update kembalian
        $('input[name="total_diterima"]').on(
            "keydown change",
            hitungKembalian
        );

        // Event ketika input cari barang diubah : buka modal
        $("input.cari-barang").on("keydown", function() {
            event.preventDefault();
            $("#modalPilihBarang").modal("show");
            $(this).val("");
        });

        // Event ketika form di submit
        $("form#formPenjualan").on("submit", function() {
            event.preventDefault();
            let url = $(this).attr("action");
            let formData = $(this).serialize();
            $.post(url, formData)
                .done((res) => {
                    Swal.fire({
                        icon: "success",
                        title: res.message,
                        showConfirmButton: false,
                        timer: 2000,
                    }).then(() => {
                        location.reload();
                    });
                })
                .fail((err) => {
                    console.log(err.responseJSON);
                    toaster.fire({
                        icon: "error",
                        title: "Data gagal disimpan",
                    });

                    return;
                });
        });
    </script>
@endpush

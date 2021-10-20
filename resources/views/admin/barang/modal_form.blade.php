<div class="modal fade" role="dialog" id="modalForm">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('barang.store') }}" id="barang-form" method="POST">
                @method('post')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Barang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="produkId">Jenis Produk</label>
                        <select name="produk_id" class="custom-select select-produk" id="produkId">
                            @foreach ($produk as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="namaBarang">Nama Barang</label>
                        <input type="text" name="nama_barang" id="namaBarang" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="satuan">Satuan</label>
                        <input type="text" name="satuan" id="satuan" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="hargaJual">Harga Jual</label>
                        <input type="number" name="harga_jual" id="hargaJual" class="form-control" autocomplete="off">
                    </div>
                    <div class="form-group mb-3">
                        <label for="diskon">Diskon</label>
                        <input type="number" value="0" name="diskon" id="diskon" class="form-control" autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="submit" class="btn btn-primary modal-submit-button">Tambah Produk</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

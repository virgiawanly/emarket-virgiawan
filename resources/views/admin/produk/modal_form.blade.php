<div class="modal fade" tabindex="-1" role="dialog" id="modalForm">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('produk.store') }}" data-toggle="validator" id="produk-form" method="POST">
                @method('post')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-0">
                        <label for="namaProduk">Nama Produk</label>
                        <input type="text" name="nama_produk" value="{{ old('nama_produk') }}" id="namaProduk"
                            placeholder="Sesuatu..." class="form-control">
                        <div class="help-block with-errors text-danger"></div>
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

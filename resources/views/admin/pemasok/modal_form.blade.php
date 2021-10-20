<div class="modal fade" role="dialog" id="modalForm">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('pelanggan.store') }}" id="barang-form" method="POST">
                @method('post')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pelanggan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="no_telp">No Telepon</label>
                        <input type="text" name="no_telp" id="no_telp" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="kota">Kota</label>
                        <input type="text" name="kota" id="kota" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="alamat">Alamat</label>
                       <textarea name="alamat" id="alamat" style="height: 100px;" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="submit" class="btn btn-primary modal-submit-button">Tambah Pelanggan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

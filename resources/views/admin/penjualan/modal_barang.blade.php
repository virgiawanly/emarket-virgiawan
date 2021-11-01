<div class="modal fade" role="dialog" id="modalPilihBarang">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="tablePilihBarang" class="table table-striped table-sm">
                    <thead>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Jenis Produk</th>
                        <th>Harga Jual</th>
                        <th>Diskon</th>
                        <th>Sisa Stok</th>
                        <th>Aksi</th>
                    </thead>
                    <tbody>
                        @foreach ($barang as $b)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$b->kode_barang}}</td>
                            <td>{{$b->nama_barang}}</td>
                            <td>{{$b->produk->nama_produk}}</td>
                            <td>{{$b->harga_jual}}</td>
                            <td>{{$b->diskon ? $b->diskon . '%' : '-'}}</td>
                            <td>{{$b->stok}}</td>
                            <td>
                                @if ($b->stok > 0)
                                    <button class="button-pilih-barang btn btn-sm btn-success" data-barang-id="{{$b->id}}"><i class="fas fa-check-circle"></i> Pilih</button>
                                @else
                                    <button class="btn btn-sm btn-secondary"><i class="fas fa-ban mr-1"></i>Kosong</button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

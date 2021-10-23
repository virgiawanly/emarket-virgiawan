<div class="modal fade" role="dialog" id="modalPilihPelanggan">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Pemasok</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="tablePilihPelanggan" class="table table-striped table-sm">
                    <thead>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. Telepon</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </thead>
                    <tbody>
                        @foreach ($pelanggan as $p)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$p->nama}}</td>
                            <td>{{$p->email}}</td>
                            <td>{{$p->no_telp}}</td>
                            <td>{{$p->alamat}}</td>
                            <td>
                                <button class="button-pilih-pelanggan btn btn-sm btn-success" data-kode-pelanggan="{{$p->kode_pelanggan}}"><i class="fas fa-check-circle"></i> Pilih</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

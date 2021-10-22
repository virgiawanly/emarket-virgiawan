<div class="modal fade" role="dialog" id="modalPilihPemasok">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Pemasok</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="tablePilihPemasok" class="table table-striped table-sm">
                    <thead>
                        <th>#</th>
                        <th>Nama Pemasok</th>
                        <th>No. Telepon</th>
                        <th>Alamat</th>
                        <th>Kota</th>
                        <th>Aksi</th>
                    </thead>
                    <tbody>
                        @foreach ($pemasok as $p)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$p->nama}}</td>
                            <td>{{$p->no_telp}}</td>
                            <td>{{$p->alamat}}</td>
                            <td>{{$p->kota}}</td>
                            <td>
                                <button class="button-pilih-pemasok btn btn-sm btn-success" data-pemasok-id="{{$p->id}}"><i class="fas fa-check-circle"></i> Pilih</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

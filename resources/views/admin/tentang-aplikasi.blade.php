@extends('admin.layouts.main')

@section('content-header')
    <h1>Tentang Aplikasi</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="/">Dashboard</a></div>
        <div class="breadcrumb-item">Tentang Aplikasi</div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Tentang</h4>
                </div>
                <div class="card-body">
                    <div> Lorem, ipsum dolor sit amet consectetur adipisicing elit. Culpa placeat id libero voluptates nulla officia hic quibusdam. Deserunt, corporis ratione accusamus explicabo perspiciatis molestias, temporibus, debitis fuga ad voluptates exercitationem.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Credits</h4>
                </div>
                <div class="card-body">
                    <div class="list-unstyled list-unstyled-border">
                        <div class="media">
                            <div class="media-icon"><i class="far fa-circle"></i></div>
                            <div class="media-body">
                                <h6>Stisla</h6>
                                <p>by <a href="https://getstisla.com/">@Muhamad Nauval Azhar</a>
                            </div>
                        </div>
                        <div class="media">
                            <div class="media-icon"><i class="far fa-circle"></i></div>
                            <div class="media-body">
                                <h6>Bootstrap</h6>
                                <p>by @mdo and @fat</p>
                            </div>
                        </div>
                        <div class="media">
                            <div class="media-icon"><i class="far fa-circle"></i></div>
                            <div class="media-body">
                                <h6>DataTables</h6>
                                <p>by @datatables</p>
                            </div>
                        </div>
                        <div class="media">
                            <div class="media-icon"><i class="far fa-circle"></i></div>
                            <div class="media-body">
                                <h6>Select2</h6>
                                <p>by <a href="https://select2.org/">@select2</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('admin.layouts.main')

@section('content-header')
    <h1>Pendaftaran User</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="/">Dashboard</a></div>
        <div class="breadcrumb-item active"><a href="/users">Users</a></div>
        <div class="breadcrumb-item">Register</div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="card-header">
                        <div class="card-title mb-0">
                            Form Register User
                        </div>
                    </div>
                    <div class="card-body py-1">
                        @include('admin.users.form_control')
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

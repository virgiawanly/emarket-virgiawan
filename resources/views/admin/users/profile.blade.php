@extends('admin.layouts.main')

@section('content-header')
    <h1>Profile</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="/">Dashboard</a></div>
        <div class="breadcrumb-item">Profile</div>
    </div>
@endsection

@section('content')
    <h2 class="section-title">Hi, {{ Auth::user()->name }}</h2>
    <p class="section-lead">
        Change information about yourself on this page.
    </p>
    <div class="row mt-0">
        <div class="col-12 col-md-12">
            <div class="card profile-widget">
                <div class="profile-widget-header">
                </div>
                <form method="post" action="/profile" enctype="multipart/form-data">
                    @csrf
                    <div class="card-header">
                        <h4>Edit Profile</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group text-center mb-5">
                            <label for="userPhoto">
                                <img alt="image" src="{{ Auth::user()->get_photo() }}"
                                    class="rounded-circle img-thumbnail" id="userPhotoPreview" style="width: 200px; height: 200px; object-fit:cover;">
                            </label>
                            <input type="file" id="userPhoto" name="photo" class="d-none">
                        </div>
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}"
                                required="">
                            @error('name')
                                <div class="form-text text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Password Lama</label>
                            <input type="password" name="old_password" class="form-control">
                            @error('password')
                                <div class="form-text text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Password Baru</label>
                            <input type="password" name="password" class="form-control">
                            @error('password')
                                <div class="form-text text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                            @error('password_confirmation')
                                <div class="form-text text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function previewUserPhoto(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#userPhotoPreview').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("input#userPhoto").change(function() {
            previewUserPhoto(this);
        });
    </script>
@endpush

@extends('layouts.app')

@section('content')
<h2><b><center>Tambah Menu</center></b></h2>
<form action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="nama_menu" class="form-label">Nama Menu</label>
        <input type="text" class="form-control @error('nama_menu') is-invalid @enderror" name="nama_menu" id="nama_menu" value="{{old('nama_menu')}}">
        @error('nama_menu')
            <div class="text-danger">{{$message}}</div>
        @enderror
    </div>
    <label for="harga" class="form-label">Harga</label>
    <div class="input-group mb-3">
        <span class="input-group-text">Rp</span>
        <input type="text" class="form-control @error('harga') is-invalid @enderror"  name="harga" id="harga" min="0" value="{{old('harga')}}" aria-label="Amount (to the nearest dollar)">
        @error('harga')
            <div class="text-danger">{{$message}}</div>
        @enderror
        <span class="input-group-text"></span>
    </div>
    <div class="mb-3">
        <label for="stok" class="form-label">Stok</label>
        <input type="text" class="form-control @error('stok') is-invalid @enderror" name="stok" id="stok" min="0" value="{{old('stok')}}">
        @error('stok')
            <div class="text-danger">{{$message}}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" id="deskripsi" rows="3">{{old('deskripsi')}}</textarea>
        @error('deskripsi')
            <div class="text-danger">{{$message}}</div>
        @enderror
    </div>
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="gambar-label">Gambar</span>
            </div>
            <div class="custom-file">
                <input type="file" class="custom-file-input" name="gambar" id="gambar">
                <label class="custom-file-label" for="gambar">Pilih file</label>
            </div>
        </div>
        @error('gambar')
            <div class="text-danger">{{$message}}</div>
        @enderror
    </div>
    <button class="btn btn-primary btn-lg btn-block" type="submit">Tambah</button>
</form>
@endsection
@push('js_after')
    <script>
        $(".custom-file-input").on("change", function () {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    </script>
@endpush

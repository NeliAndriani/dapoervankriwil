@extends('layouts.app')

@section('content')
<h2><b><center>Tambah Supplier</center></b></h2>
<form action="{{ route('suppliers.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="nama_menu" class="form-label">Nama Supplier</label>
        <input type="text" class="form-control @error('nama_supplier') is-invalid @enderror" name="nama_supplier" id="nama_supplier" value="{{old('nama_supplier')}}">
        @error('nama_supplier')
            <div class="text-danger">{{$message}}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="no_telepon" class="form-label">No Telepon</label>
        <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" name="no_telepon" id="no_telepon" placeholder="Contoh: 081234567890" value="{{old('no_telepon')}}">
        @error('no_telepon')
            <div class="text-danger">{{$message}}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="alamat" class="form-label">Alamat</label>
        <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" id="alamat" rows="3">{{old('alamat')}}</textarea>
        @error('alamat')
            <div class="text-danger">{{$message}}</div>
        @enderror
    </div>
    <button class="btn btn-primary btn-lg btn-block" type="submit">Tambah</button>
</form>
@endsection

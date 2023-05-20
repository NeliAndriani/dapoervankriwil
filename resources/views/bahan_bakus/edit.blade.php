@extends('layouts.app')

@section('content')
<h2><b><center>Ubah Bahan Baku</center></b></h2>
<form action="{{ route('bahan_bakus.update', ['bahan_baku' => $bahan_baku->bahan_baku_id]) }}" method="POST" enctype="multipart/form-data">
    @method('PATCH')
    @csrf
    <div class="mb-3">
        <label for="nama_menu" class="form-label">Nama Baku Baku</label>
        <input type="text" class="form-control @error('nama_bahan_baku') is-invalid @enderror" name="nama_bahan_baku" id="nama_bahan_baku" value="{{old('nama_bahan_baku') ?? $bahan_baku->nama_bahan_baku }}">
        @error('nama_bahan_baku')
            <div class="text-danger">{{$message}}</div>
        @enderror
    </div>
    <label for="harga" class="form-label">Harga</label>
    <div class="input-group mb-3">
        <span class="input-group-text">Rp</span>
        <input type="text" class="form-control @error('harga') is-invalid @enderror"  name="harga" id="harga" min="1" value="{{old('harga') ?? $bahan_baku->harga }}" aria-label="Amount (to the nearest dollar)">
        @error('harga')
            <div class="text-danger">{{$message}}</div>
        @enderror
        <span class="input-group-text"></span>
    </div>
    <div class="mb-3">
        <label for="stok" class="form-label">Stok</label>
        <input type="text" class="form-control @error('stok') is-invalid @enderror" name="stok" id="stok" value="{{old('stok') ?? $bahan_baku->stok }}">
        @error('stok')
            <div class="text-danger">{{$message}}</div>
        @enderror
    </div>
    <div class="mb-3">
    <label for="satuan" class="col-sm-2 col-form-label">Satuan:</label>
                <select class="form-select form-select-sm @error('satuan') is-invalid @enderror" name="satuan" style="width: 280px;">
                    <option value="">Pilih Satuan</option>
                    <option value="kg" {{ (old('satuan') ?? $bahan_baku->satuan) == 'kg' ? 'selected' : '' }}>per kilo</option>
                    <option value="gr" {{ (old('satuan') ?? $bahan_baku->satuan) == 'gr' ? 'selected' : '' }}>per gram</option>
                    <option value="lt" {{ (old('satuan') ?? $bahan_baku->satuan) == 'lt' ? 'selected' : '' }}>per liter</option>
                    <option value="bh" {{ (old('satuan') ?? $bahan_baku->satuan) == 'bh' ? 'selected' : '' }}>per buah</option>
                </select>
    </div>
    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" id="deskripsi" rows="3">{{old('deskripsi') ?? $bahan_baku->deskripsi }}</textarea>
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

    @if ($bahan_baku->gambar)
        <div class="card card-primary">
            <div class="card-body">
                <div class="filter-container p-0 row" >
                    <div class="filtr-item col-sm-2">
                        <a href="{{Storage::url($bahan_baku->gambar)}}"
                            data-toggle="lightbox">
                            <img src="{{Storage::url($bahan_baku->gambar)}}" class="img-fluid mb-2" />
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    <button class="btn btn-primary btn-lg btn-block" type="submit">Ubah</button>
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

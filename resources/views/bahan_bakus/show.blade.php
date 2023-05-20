@extends('layouts.app')

@section('content')
<h2><center><b>{{$bahan_baku->nama_bahan_baku}}</b></center></h2>
    <div class="card-body text-center" style="display: flex; justify-content: center; align-items: center;">
        <div style="text-align: left; margin-right: 100px;">
            <p class="card-text"><smalll class="text">Harga: Rp.{{$bahan_baku->harga}}</smalll></p>
            <p class="card-text"><smalll class="text">Satuan: /{{$bahan_baku->satuan}}</smalll></p>
            <p class="card-text"><smalll class="text">Stok: {{$bahan_baku->stok}}</smalll></p>
            <p class="card-text"><smalll class="text">Deskripsi: {{$bahan_baku->deskripsi}}</smalll></p>
        </div>
    </div>
    <img src="{{Storage::url($bahan_baku->gambar)}}" class="rounded mx-auto d-block" width="400" height="250">
    <br><br>
    <p class="card-text text-center"><smalll class="text">@dapoervankriwil</smalll></p>
@endsection

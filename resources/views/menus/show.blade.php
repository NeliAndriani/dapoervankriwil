@extends('layouts.app')

@section('content')
<h2><center><b>{{$menu->nama_menu}}</b></center></h2>
    <div class="card-body text-center" style="display: flex; justify-content: center; align-items: center;">
        <div style="text-align: left; margin-right: 200px;">
            <p class="card-text">Harga: Rp. {{$menu->harga}}</p>
            <p class="card-text">Stok: {{$menu->stok}} porsi</p>
            <p class="card-text"><smalll class="text">Deskripsi: {{$menu->deskripsi}}</smalll></p>
        </div>
    </div>
    <img src="{{Storage::url($menu->gambar)}}" class="rounded mx-auto d-block" width="400" height="250">
    <br><br>
    <p class="card-text text-center"><smalll class="text">@dapoervankriwil</smalll></p>
@endsection

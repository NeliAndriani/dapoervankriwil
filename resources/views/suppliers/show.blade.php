@extends('layouts.app')

@section('content')
<h2><center><b>{{$supplier->nama_supplier}}</b></center></h2>
    <div class="card-body text-center" style="display: flex; justify-content: center; align-items: center;">
        <div style="text-align: left; margin-right: 200px;">
            <p class="card-text">No Telepon: {{$supplier->no_telepon}}</p>
            <p class="card-text"><smalll class="text">Alamat: {{$supplier->alamat}}</smalll></p>
        </div>
    </div>
    <br><br>
    <p class="card-text text-center"><smalll class="text">@dapoervankriwil</smalll></p>
@endsection

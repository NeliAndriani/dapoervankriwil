@extends('layouts.app')

@section('content')
@if (session()->has('success'))
    <div class="alert alert-success" role="alert">
        {{session()->get('success')}}
    </div>
@endif
<div class="row mb-3">
    <h2><b><center>Transaksi Waste Menu</center></b></h2><br>
    <div class="col d-flex justify-content-end">
        <a href="{{route('waste_menus.create')}}">
            <button class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Waste Menu</button>
        </a>
    </div>
</div>
<div class="table-responsive">
<table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">No</th>
        <th scope="col">Id Waste Menu</th>
        <th scope="col">Detil Waste Menu</th>
        <th scope="col">Tanggal</th>
        <th scope="col">Aksi</th>
      </tr>
    </thead>
    <tbody>
        @forelse ($waste_menus as $key => $waste_menu)
      <tr>
        <th scope="row">{{$waste_menus->firstItem() + $key}}</th>
        <td>{{ $waste_menu->waste_menu_id ?? '' }}</td>
        <td>
            <ul>
            @foreach($waste_menu->menus as $item)
                <li><i class="bi bi-dot"></i>{{ $item->nama_menu }} x {{ $item->pivot->jumlah }} porsi</li>
            @endforeach
            </ul>
        </td>
        <td>{{$waste_menu->tanggal}}</td>
            <td class="d-flex justify-content-between">
            <div class="btn-group" role="group">
              <a href="{{ route('waste_menus.edit', $waste_menu->waste_menu_id) }}">
                <button class="btn btn-warning btn-sm me-2">
                  <i class="bi bi-pencil-square"></i> Ubah
                </button>
              </a>
        </div>
          </td>
      </tr>
      @empty
      <tr>
        <td align="center" colspan="5">Tidak ada data.</td>
      </tr>
      @endforelse
    </tbody>
  </table>
  <div>
    Showing
    {{$waste_menus->firstItem()}}
    to
    {{$waste_menus->lastItem()}}
    of
    {{$waste_menus->total()}}
    entries
  </div>
    <div class="d-flex justify-content-end">
        {{ $waste_menus->links() }}
    </div>
</div>
@endsection

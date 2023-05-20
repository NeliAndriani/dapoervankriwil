@extends('layouts.app')

@section('content')
@if (session()->has('success'))
    <div class="alert alert-success" role="alert">
        {{session()->get('success')}}
    </div>
@endif
<div class="row mb-3">
    <h2><b><center>Transaksi Penjualan Menu</center></b></h2>
    <div class="col d-flex justify-content-end">
        <a href="{{route('penjualan_menus.create')}}">
            <button class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Penjualan Menu</button>
        </a>
    </div>
</div>
<div class="table-responsive">
<table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">No</th>
        <th scope="col">Id Penjualan Menu</th>
        <th scope="col">Detil Penjualan</th>
        <th scope="col">Tanggal</th>
        <th scope="col">Metode Bayar</th>
        <th scope="col">Total Harga</th>
        <th scope="col">Aksi</th>
      </tr>
    </thead>
    <tbody>
        @forelse ($penjualan_menus as $key => $penjualan_menu)
      <tr>
        <th scope="row">{{$penjualan_menus->firstItem() + $key}}</th>
        <td>{{ $penjualan_menu->penjualan_menu_id ?? '' }}</td>
        <td>
            <ul>
            @foreach($penjualan_menu->menus as $item)
                <li><i class="bi bi-dot"></i>{{ $item->nama_menu }} ({{ $item->pivot->jumlah }} x Rp.{{ number_format ($item->harga, 0, '.', '.') }})</li>
                @endforeach
            </ul>
        </td>
        <td>{{ $penjualan_menu->tanggal}}</td>
        <td>
            @if ($penjualan_menu->metode_bayar == 0)
                Tunai
            @elseif ($penjualan_menu->metode_bayar == 1)
                Qris
            @else
                Unknown
            @endif
        </td>
        <td>Rp.{{ number_format($penjualan_menu->menus->sum(function($menu) { return $menu->pivot->jumlah * $menu->harga; }), 0, '.', '.') }}</td>
            <td class="d-flex justify-content-between">
            <div class="btn-group" role="group">
                @role('pemilik')
              <a href="{{ route('penjualan_menus.edit', $penjualan_menu->penjualan_menu_id) }}">
                <button class="btn btn-warning btn-sm me-2">
                  <i class="bi bi-pencil-square"></i> Ubah
                </button>
              </a>
              @endrole
              @if ($penjualan_menu->menus->pluck('pivot.rating_menu')->contains(null))
              <a href="{{ route('penjualan_menus.rating', $penjualan_menu->penjualan_menu_id) }}">
                  <button class="btn btn-secondary btn-sm me-2">
                      <i class="bi bi-star"></i> Rating
                  </button>
              </a>
              @else
              <button class="btn btn-secondary btn-sm me-2" disabled>
                  Sudah di Rating
              </button>
              @endif
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
    {{$penjualan_menus->firstItem()}}
    to
    {{$penjualan_menus->lastItem()}}
    of
    {{$penjualan_menus->total()}}
    entries
  </div>
    <div class="d-flex justify-content-end">
        {{ $penjualan_menus->links() }}
    </div>
</div>

@endsection

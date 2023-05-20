@extends('layouts.app')

@section('content')
@if (session()->has('success'))
    <div class="alert alert-success" role="alert">
        {{session()->get('success')}}
    </div>
@endif
<div class="row mb-3">
    <h2><b><center>Transaksi Pembelian Menu Supplier</center></b></h2>
    <div class="col d-flex justify-content-end">
        <a href="{{route('pembelian_menu_suppliers.create')}}">
            <button class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Pembelian Menu Supplier</button>
        </a>
    </div>
</div>
<div class="table-responsive">
<table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">No</th>
        <th scope="col">Id Pembelian Menu Supplier</th>
        <th scope="col">Detil Pembelian Menu Supplier</th>
        <th scope="col">Tanggal</th>
        <th scope="col">Total Harga</th>
        <th scope="col">Aksi</th>
      </tr>
    </thead>
    <tbody>
        @forelse ($pembelian_menu_suppliers as $key => $pembelian_menu_supplier)
      <tr>
        <th scope="row">{{$pembelian_menu_suppliers->firstItem() + $key}}</th>
        <td>{{ $pembelian_menu_supplier->pembelian_menu_supplier_id ?? '' }}</td>
        <td>
            <ul>
            @foreach($pembelian_menu_supplier->menus as $item)
                <li><i class="bi bi-dot"></i>{{ $item->nama_menu }} ({{ $item->pivot->jumlah }} x Rp.{{ number_format ($item->pivot->harga, 0, '.', '.') }})</li>
            @endforeach
            </ul>
        </td>
        <td>{{ $pembelian_menu_supplier->tanggal}}</td>
        <td>Rp.{{ number_format($pembelian_menu_supplier->menus->sum(function($menu) { return $menu->pivot->jumlah * $menu->pivot->harga; }), 0, '.', '.') }}</td>
            <td class="d-flex justify-content-between">
            <div class="btn-group" role="group">
                @role('pemilik')
              <a href="{{ route('pembelian_menu_suppliers.edit', $pembelian_menu_supplier->pembelian_menu_supplier_id) }}">
                <button class="btn btn-warning btn-sm me-2">
                  <i class="bi bi-pencil-square"></i> Ubah
                </button>
              </a>
              @endrole
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
    {{$pembelian_menu_suppliers->firstItem()}}
    to
    {{$pembelian_menu_suppliers->lastItem()}}
    of
    {{$pembelian_menu_suppliers->total()}}
    entries
  </div>
    <div class="d-flex justify-content-end">
        {{ $pembelian_menu_suppliers->links() }}
    </div>
</div>

@endsection

@extends('layouts.app')

@section('content')
@if (session()->has('success'))
    <div class="alert alert-success" role="alert">
        {{session()->get('success')}}
    </div>
@endif
<div class="row mb-3">
    <h2><b><center>Transaksi Pembelian Bahan Baku</center></b></h2><br>
    <div class="col d-flex justify-content-end">
        <a href="{{route('pembelian_bahan_bakus.create')}}">
            <button class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Pembelian bahan baku</button>
        </a>
    </div>
</div>
<div class="table-responsive">
<table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">No</th>
        <th scope="col">Id Pembelian Bahan Baku</th>
        <th scope="col">Detil Pembelian</th>
        <th scope="col">Tanggal</th>
        <th scope="col">Total Harga</th>
        <th scope="col">Aksi</th>
      </tr>
    </thead>
    <tbody>
        @forelse ($pembelian_bahan_bakus as $key => $pembelian_bahan_baku)
      <tr>
        <th scope="row">{{$pembelian_bahan_bakus->firstItem() + $key}}</th>
        <td>{{ $pembelian_bahan_baku->pembelian_bahan_baku_id ?? '' }}</td>
        <td>
            <ul>
            @foreach($pembelian_bahan_baku->bahan_bakus as $item)
                <li><i class="bi bi-dot"></i>{{ $item->nama_bahan_baku }} ({{ $item->pivot->jumlah }} {{$item->satuan}} x Rp.{{ number_format ($item->pivot->harga, 0, '.', '.') }})</li>
            @endforeach
            </ul>
        </td>
        <td>{{$pembelian_bahan_baku->tanggal}}</td>
        <td>Rp.{{ number_format ($pembelian_bahan_baku->bahan_bakus->sum(function($bahan_baku) { return $bahan_baku->pivot->jumlah * $bahan_baku->pivot->harga; }), 0, '.', '.')  }}</td>
            <td class="d-flex justify-content-between">
            <div class="btn-group" role="group">
                @role('pemilik')
              <a href="{{ route('pembelian_bahan_bakus.edit', $pembelian_bahan_baku->pembelian_bahan_baku_id) }}">
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
    {{$pembelian_bahan_bakus->firstItem()}}
    to
    {{$pembelian_bahan_bakus->lastItem()}}
    of
    {{$pembelian_bahan_bakus->total()}}
    entries
  </div>
    <div class="d-flex justify-content-end">
        {{ $pembelian_bahan_bakus->links() }}
    </div>
</div>
@endsection

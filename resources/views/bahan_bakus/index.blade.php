@extends('layouts.app')

@section('content')
@if (session()->has('success'))
    <div class="alert alert-success" role="alert">
        {{session()->get('success')}}
    </div>
@endif
<div class="row mb-3">
    <h2><b><center>Daftar Bahan Baku</center></b></h2>
    <div class="col d-flex justify-content-end">
        <a href="{{route('bahan_bakus.create')}}">
            <button class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Bahan Baku</button>
        </a>
    </div>
</div>
<div class="table-responsive">
<table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">No</th>
        <th scope="col">Bahan Baku Id</th>
        <th scope="col">Nama Bahan Baku</th>
        <th scope="col">Harga</th>
        <th scope="col">Satuan</th>
        <th scope="col">Stok</th>
        <th scope="col">Deskripsi</th>
        <th scope="col">Aksi</th>
      </tr>
    </thead>
    <tbody>
        @forelse ($bahan_bakus as $key => $bahan_baku)
      <tr>
        <th scope="row">{{$bahan_bakus->FirstItem() + $key}}</th>
        <td>{{$bahan_baku->bahan_baku_id}}</td>
        <td>{{$bahan_baku->nama_bahan_baku}}</td>
        <td>Rp.{{ number_format($bahan_baku->harga, 0, '.', '.') }}</td>
        <td>/{{$bahan_baku->satuan}}</td>
        <td>{{$bahan_baku->stok}}</td>
        <td>{{$bahan_baku->deskripsi}}</td>
        <td class="d-flex justify-content-between">
            <div class="btn-group" role="group">
              <a href="{{ route('bahan_bakus.show',['bahan_baku' => $bahan_baku->bahan_baku_id]) }}">
                <button class="btn btn-secondary btn-sm me-2">
                  <i class="bi bi-eye"></i> Lihat
                </button>
              </a>
              <a href="{{ route('bahan_bakus.edit', $bahan_baku->bahan_baku_id) }}">
                <button class="btn btn-warning btn-sm me-2">
                  <i class="bi bi-pencil-square"></i> Ubah
                </button>
              </a>
            </div>
            <form action="{{route('bahan_bakus.destroy', $bahan_baku->bahan_baku_id)}}" method="POST">
              <button type="submit" class="btn btn-danger btn-sm me-2">
                <i class="bi bi-trash"></i> Hapus
              </button>
              @method('DELETE')
              @csrf
            </form>
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
    {{$bahan_bakus->firstItem()}}
    to
    {{$bahan_bakus->lastItem()}}
    of
    {{$bahan_bakus->total()}}
    entries
  </div>
    <div class="d-flex justify-content-end">
        {{ $bahan_bakus->links() }}
    </div>
</div>
@endsection

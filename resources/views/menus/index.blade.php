@extends('layouts.app')

@section('content')
@if (session()->has('success'))
    <div class="alert alert-success" role="alert">
        {{session()->get('success')}}
    </div>
@endif
<div class="row mb-3">
    <h2><b><center>Daftar Menu</center></b></h2>
    <div class="col d-flex justify-content-end">
        <a href="{{route('menus.create')}}">
            <button class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Menu</button>
        </a>
    </div>
</div>
<div class="table-responsive">
<table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">No</th>
        <th scope="col">Menu Id</th>
        <th scope="col">Nama Menu</th>
        <th scope="col">Harga</th>
        <th scope="col">Stok</th>
        <th scope="col">Deskripsi</th>
        <th scope="col">Aksi</th>
      </tr>
    </thead>
    <tbody>
        @forelse ($menus as $key => $menu)
      <tr>
        <th scope="row">{{$menus->firstItem() + $key}}</th>
        <td>{{$menu->menu_id}}</td>
        <td>{{$menu->nama_menu}}</td>
        <td>Rp.{{ number_format($menu->harga, 0, '.', '.') }}</td>
        <td>{{$menu->stok}} Porsi</td>
        <td>{{$menu->deskripsi}}</td>
        <td class="d-flex justify-content-between">
            <div class="btn-group" role="group">
              <a href="{{ route('menus.show', ['menu' => $menu->menu_id]) }}">
                <button class="btn btn-secondary btn-sm me-2">
                  <i class="bi bi-eye"></i> Lihat
                </button>
              </a>
              <a href="{{ route('menus.edit', $menu->menu_id) }}">
                <button class="btn btn-warning btn-sm me-2">
                  <i class="bi bi-pencil-square"></i> Ubah
                </button>
              </a>
            </div>
            <form action="{{route('menus.destroy', $menu->menu_id)}}" method="POST">
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
    {{$menus->firstItem()}}
    to
    {{$menus->lastItem()}}
    of
    {{$menus->total()}}
    entries
  </div>
    <div class="d-flex justify-content-end">
        {{ $menus->links() }}
    </div>
</div>
@endsection




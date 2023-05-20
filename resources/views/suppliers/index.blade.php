@extends('layouts.app')

@section('content')
@if (session()->has('success'))
    <div class="alert alert-success" role="alert">
        {{session()->get('success')}}
    </div>
@endif
<div class="row mb-3">
    <h2><b><center>Daftar Supplier</center></b></h2>
    <div class="col d-flex justify-content-end">
        <a href="{{route('suppliers.create')}}">
            <button class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Supplier</button>
        </a>
    </div>
</div>
<div class="table-responsive">
<table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">No</th>
        <th scope="col">Supplier Id</th>
        <th scope="col">Nama Supplier</th>
        <th scope="col">No Telepon</th>
        <th scope="col">Alamat</th>
        <th scope="col">Aksi</th>
      </tr>
    </thead>
    <tbody>
        @forelse ($suppliers as $key => $supplier)
      <tr>
        <th scope="row">{{$suppliers->firstItem() + $key}}</th>
        <td>{{$supplier->supplier_id}}</td>
        <td>{{$supplier->nama_supplier}}</td>
        <td>{{$supplier->no_telepon}}</td>
        <td>{{$supplier->alamat}}</td>
        <td class="d-flex justify-content-between">
            <div class="btn-group" role="group">
              <a href="{{ route('suppliers.show',['supplier' => $supplier->supplier_id]) }}">
                <button class="btn btn-secondary btn-sm me-2">
                  <i class="bi bi-eye"></i> Lihat
                </button>
              </a>
              <a href="{{ route('suppliers.edit', $supplier->supplier_id) }}">
                <button class="btn btn-warning btn-sm me-2">
                  <i class="bi bi-pencil-square"></i> Ubah
                </button>
              </a>
            </div>
            <form action="{{route('suppliers.destroy', $supplier->supplier_id)}}" method="POST">
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
    {{$suppliers->firstItem()}}
    to
    {{$suppliers->lastItem()}}
    of
    {{$suppliers->total()}}
    entries
  </div>
    <div class="d-flex justify-content-end">
        {{ $suppliers->links() }}
    </div>
</div>
@endsection


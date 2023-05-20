@extends('layouts.app')

@section('content')
@if (session()->has('success'))
    <div class="alert alert-success" role="alert">
        {{session()->get('success')}}
    </div>
@endif
<div class="row mb-3">
    <h2><b><center>Laporan Pembelian Menu Supplier</center></b></h2>
    <div class="col d-flex justify-content-end">
    </div>
</div>
<form action="{{ route('pembelian_menu_suppliers.laporan') }}" method="GET">
    <div class="row">
        <div class="col-md-4">
            <label for="tanggal_awal">Tanggal Awal:</label>
            <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" value="{{ $tanggalAwal ?: '' }}" required>
        </div>
        <div class="col-md-4">
            <label for="tanggal_akhir">Tanggal Akhir:</label>
            <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="{{ $tanggalAkhir ?: '' }}" required>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </div>
</form>
<div class="row mt-3">
    <div class="col-md-2 ml-auto">
        <a href="{{route('pembelian_menu_suppliers.downloadpdf', ['tanggal_awal' => $tanggalAwal, 'tanggal_akhir' => $tanggalAkhir])}}" target="_blank">
            <button class="btn btn-primary bi bi-printer"> Cetak PDF</button>
        </a>
    </div>
    </div>
<br>
<div class="table-responsive">
<table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">No</th>
        <th scope="col">Id Pembelian Menu Supplier</th>
        <th scope="col">Nama Supplier</th>
        <th scope="col">Detil Pembelian Menu Supplier</th>
        <th scope="col">Tanggal</th>
        <th scope="col">Total Harga</th>
      </tr>
    </thead>
    <tbody>
        @forelse ($pembelian_menu_suppliers as $pembelian_menu_supplier)
      <tr>
        <th scope="row">{{$loop->iteration}}</th>
        <td>{{ $pembelian_menu_supplier->pembelian_menu_supplier_id ?? '' }}</td>
        <td>
            @if ($pembelian_menu_supplier->suppliers)
                        {{ $pembelian_menu_supplier->suppliers->nama_supplier }}
                    @else
                        N/A
                    @endif
        </td>
        <td>
            @foreach($pembelian_menu_supplier->menus as $item)
            <i class="bi bi-dot"></i> {{ $item->nama_menu }} ({{ $item->pivot->jumlah }} x Rp.{{ number_format ($item->pivot->harga, 0, '.', '.') }})<br>
            @endforeach
        </td>
        <td>{{ $pembelian_menu_supplier->tanggal}}</td>
        <td>Rp.{{ number_format($pembelian_menu_supplier->menus->sum(function($menu) { return $menu->pivot->jumlah * $menu->pivot->harga; }), 0, '.', '.') }}</td>
      </tr>
      @empty
      <tr>
        <td align="center" colspan="5">Tidak ada data.</td>
      </tr>
      @endforelse
    </tbody>
  </table>
  <div class="ml-auto text-right">
    <strong>Total:</strong> <span class="badge text-bg-success" id="total_harga" style="font-size: 14px;">Rp.0</span>
    </div>
</div>

@endsection
<script>
    window.addEventListener('DOMContentLoaded', function() {
       var tanggalAwalInput = document.getElementById('tanggal_awal');
       var tanggalAkhirInput = document.getElementById('tanggal_akhir');
       var totalHargaElement = document.getElementById('total_harga');

       var tanggalAwal = localStorage.getItem('tanggal_awal');
       var tanggalAkhir = localStorage.getItem('tanggal_akhir');

       // Setel nilai input tanggal awal dan tanggal akhir dari penyimpanan lokal
       if (tanggalAwal && tanggalAkhir) {
           tanggalAwalInput.value = tanggalAwal;
           tanggalAkhirInput.value = tanggalAkhir;
       }

       // Simpan nilai input tanggal awal dan tanggal akhir saat berubah
       tanggalAwalInput.addEventListener('change', function() {
           localStorage.setItem('tanggal_awal', this.value);
           hitungTotalOmzet();
       });

       tanggalAkhirInput.addEventListener('change', function() {
           localStorage.setItem('tanggal_akhir', this.value);
           hitungTotalOmzet();
       });

       // Hitung total omzet saat halaman dimuat ulang
       hitungTotalOmzet();

       function hitungTotalOmzet() {
           var totalHarga = 0;
           var totalHargaElements = document.querySelectorAll('.table tbody tr td:last-child');

           for (var i = 0; i < totalHargaElements.length; i++) {
               var harga = parseInt(totalHargaElements[i].textContent.replace(/\D/g, ''));

               if (!isNaN(harga)) {
                   totalHarga += harga;
               }
           }

           totalHargaElement.textContent = 'Rp.' + (isNaN(totalHarga) ? '0' : formatNumber(totalHarga));
       }

       function formatNumber(number) {
           return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
       }
   });

       window.addEventListener('beforeunload', function() {
           var tanggalAwalInput = document.getElementById('tanggal_awal');
           var tanggalAkhirInput = document.getElementById('tanggal_akhir');

           tanggalAwalInput.value = '';
           tanggalAkhirInput.value = '';
           localStorage.removeItem('tanggal_awal');
           localStorage.removeItem('tanggal_akhir');
       });
    </script>

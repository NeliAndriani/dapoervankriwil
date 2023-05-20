@extends('layouts.app')

@section('content')
@if (session()->has('success'))
    <div class="alert alert-success" role="alert">
        {{session()->get('success')}}
    </div>
@endif
<div class="row mb-3">
    <h2><b><center>Laporan Waste Menu</center></b></h2><br>
    <div class="col d-flex justify-content-end">
    </div>
</div>
<form action="{{ route('waste_menus.laporan') }}" method="GET">
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
        <a href="{{route('waste_menus.downloadpdf', ['tanggal_awal' => $tanggalAwal, 'tanggal_akhir' => $tanggalAkhir])}}" target="_blank">
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
        <th scope="col">Id Waste Menu</th>
        <th scope="col">Id Menu</th>
        <th scope="col">Detil Waste Menu</th>
        <th scope="col">Jumlah</th>
        <th scope="col">Tanggal</th>
      </tr>
    </thead>
    <tbody>
        @forelse ($waste_menus as $waste_menu)
      <tr>
        <th scope="row">{{$loop->iteration}}</th>
        <td>{{ $waste_menu->waste_menu_id ?? '' }}</td>
        <td>
            @foreach($waste_menu->menus as $item)
            <i class="bi bi-dot"></i> {{ $item->menu_id }}<br>
             @endforeach
        </td>
        <td>
            @foreach($waste_menu->menus as $item)
            <i class="bi bi-dot"></i> {{ $item->nama_menu }}<br>
            @endforeach
        </td>
        <td>
            @foreach($waste_menu->menus as $item)
            <i class="bi bi-dot"></i> {{ $item->pivot->jumlah }} porsi<br>
             @endforeach
        </td>
        <td>{{$waste_menu->tanggal}}</td>
      </tr>
      @empty
      <tr>
        <td align="center" colspan="5">Tidak ada data.</td>
      </tr>
      @endforelse
    </tbody>
  </table>
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

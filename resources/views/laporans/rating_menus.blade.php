@extends('layouts.app')

@section('content')
@if (session()->has('success'))
    <div class="alert alert-success" role="alert">
        {{session()->get('success')}}
    </div>
@endif
<div class="row mb-3">
    <h2><b><center>Laporan Rating Menu</center></b></h2>
    <div class="col d-flex justify-content-end">
    </div>
</div>
<form action="{{ route('rating_menus.laporan') }}" method="GET">
    <div class="row">
        <div class="col-md-4">
            <label for="rating_min">Rating Min:</label>
            <input type="number" step="0.1" class="form-control" id="rating_min" name="rating_min" min="0.1" max="5" value="{{ $ratingMin ?: '' }}" required>
        </div>
        <div class="col-md-4">
            <label for="rating_max">Rating Max:</label>
            <input type="number" step="0.1" class="form-control" id="rating_max" name="rating_max" min="0" max="5" value="{{ $ratingMax ?: '' }}" required>
        </div>
        <div class="col-md-2 d-flex align-self-end">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </div>
</form>
<div class="row mt-3">
<div class="col-md-2 ml-auto">
    <a href="{{route('penjualan_menus.downloadpdf_rating', ['rating_min' => $ratingMin, 'rating_max' => $ratingMax])}}" target="_blank">
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
        <th scope="col">Id Menu</th>
        <th scope="col">Nama Menu</th>
        <th scope="col">Rating Menu</th>
        <th scope="col">Jumlah Review</th>
      </tr>
    </thead>
    <tbody>
        @forelse ($menus as $menu)
      <tr>
        <th scope="row">{{$loop->iteration}}</th>
        <td>{{ $menu->menu_id ?? '' }}</td>
        <td>{{$menu->nama_menu}}</td>
        <td>
            @php
                $rating = $menu->detil_penjualan_menus->avg('rating_menu');
                @endphp

                @if ($rating)
                    @for ($i = 1; $i <= $rating; $i++)
                    @endfor
                    {{ $rating }}
                @else
                    N/A
                @endif
        </td>
        <td>{{ $menu->detil_penjualan_menus->whereNotNull('rating_menu')->count() }}</td>
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
    var ratingMinInput = document.getElementById('rating_min');
    var ratingMaxInput = document.getElementById('rating_max');

    // Set initial values from localStorage if available
    var ratingMin = localStorage.getItem('rating_min');
    var ratingMax = localStorage.getItem('rating_max');
    if (ratingMin !== null && ratingMin !== '') {
        ratingMinInput.value = ratingMin;
    }
    if (ratingMax !== null && ratingMax !== '') {
        ratingMaxInput.value = ratingMax;
    }

    // Save input values to localStorage on change
    ratingMinInput.addEventListener('input', function() {
        localStorage.setItem('rating_min', this.value);
    });
    ratingMaxInput.addEventListener('input', function() {
        localStorage.setItem('rating_max', this.value);
    });

    // Save input values to localStorage when filter button is clicked
    var filterForm = document.getElementById('filter_form');
    filterForm.addEventListener('submit', function() {
        if (ratingMinInput.value === '') {
            localStorage.removeItem('rating_min');
        }
        if (ratingMaxInput.value === '') {
            localStorage.removeItem('rating_max');
        }
    });

    // Clear localStorage when the page is unloaded
    window.addEventListener('beforeunload', function() {
        localStorage.removeItem('rating_min');
        localStorage.removeItem('rating_max');
    });

     // Check if reset parameter is present in the URL
     var urlParams = new URLSearchParams(window.location.search);
    var resetParam = urlParams.get('reset');
    if (resetParam === 'true') {
        localStorage.removeItem('rating_min');
        localStorage.removeItem('rating_max');
    }
});

// Clear localStorage when navigating to a different page
window.addEventListener('unload', function() {
    localStorage.removeItem('rating_min');
    localStorage.removeItem('rating_max');
});

});

</script>

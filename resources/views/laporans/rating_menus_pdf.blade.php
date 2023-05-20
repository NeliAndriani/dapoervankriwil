<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

<div class="row mb-3">
    <h1>Dapoer Van Kriwil</h1>
    <h5>Jl. Mekar Harum No.34</h5>
    <h6>Mekar Wangi, Bojongloa Kidul, Kota Bandung, Jawa Barat.</h6><br><br>
    <h3><b><center>Laporan Rating Menu</center></b></h3>
    <div class="col d-flex justify-content-end text-right">
        <strong>Rating Minimal:</strong> {{ $ratingMin }}<br>
        <strong>Rating Maksimal:</strong> {{ $ratingMax }}
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



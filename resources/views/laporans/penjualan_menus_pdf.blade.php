<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

<div class="row mb-3">
    <h1>Dapoer Van Kriwil</h1>
    <h5>Jl. Mekar Harum No.34</h5>
    <h6>Mekar Wangi, Bojongloa Kidul, Kota Bandung, Jawa Barat.</h6><br><br>
    <h3><b><center>Laporan Penjualan Menu</center></b></h3>
    <div class="col d-flex justify-content-end text-right">
        <strong>Tanggal Awal:</strong> {{ $tanggalAwal }}<br>
        <strong>Tanggal Akhir:</strong> {{ $tanggalAkhir }}
    </div>
</div>
<br>
<div class="table-responsive">
<table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">No</th>
        <th scope="col">Id Penjualan Menu</th>
        <th scope="col">Detil Penjualan</th>
        <th scope="col">Jumlah</th>
        <th scope="col">Harga</th>
        <th scope="col">Tanggal</th>
        <th scope="col">Metode Bayar</th>
        <th scope="col">Total Harga</th>
      </tr>
    </thead>
    <tbody>
        @forelse ($penjualan_menus as $penjualan_menu)
      <tr>
        <th scope="row">{{$loop->iteration}}</th>
        <td>{{ $penjualan_menu->penjualan_menu_id ?? '' }}</td>
        <td>
            @foreach($penjualan_menu->menus as $item)
            <i class="bi bi-dot"></i>  {{ $item->nama_menu }}<br>
            @endforeach
        </td>
        <td>
            @foreach($penjualan_menu->menus as $item)
                {{ $item->pivot->jumlah }} porsi <br>
            @endforeach
        </td>
        <td>
            @foreach($penjualan_menu->menus as $item)
                Rp.{{ number_format ($item->harga, 0, '.', '.') }} <br>
            @endforeach
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
    </tr>
      @empty
      <tr>
        <td align="center" colspan="5">Tidak ada data.</td>
      </tr>
      @endforelse
    </tbody>
  </table>
  <div class="ml-auto text-right">
    <strong>Total Omzet:</strong> <span>Rp.{{ number_format($totalOmzet, 0, '.', '.') }}</span>
    </div>
</div>


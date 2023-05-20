<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

<div class="row mb-3">
    <h1>Dapoer Van Kriwil</h1>
    <h5>Jl. Mekar Harum No.34</h5>
    <h6>Mekar Wangi, Bojongloa Kidul, Kota Bandung, Jawa Barat.</h6><br><br>
    <h3><b><center>Laporan Pembelian Menu Supplier</center></b></h3>
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


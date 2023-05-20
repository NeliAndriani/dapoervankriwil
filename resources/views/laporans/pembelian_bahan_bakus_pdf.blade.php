<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

<div class="row mb-3">
    <h1>Dapoer Van Kriwil</h1>
    <h5>Jl. Mekar Harum No.34</h5>
    <h6>Mekar Wangi, Bojongloa Kidul, Kota Bandung, Jawa Barat.</h6><br><br>
    <h3><b><center>Laporan Pembelian Bahan Baku</center></b></h3>
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
        <th scope="col">Id Pembelian Bahan Baku</th>
        <th scope="col">Detil Pembelian</th>
        <th scope="col">Tanggal</th>
        <th scope="col">Total Harga</th>
      </tr>
    </thead>
    <tbody>
        @forelse ($pembelian_bahan_bakus as $pembelian_bahan_baku)
      <tr>
        <th scope="row">{{$loop->iteration}}</th>
        <td>{{ $pembelian_bahan_baku->pembelian_bahan_baku_id ?? '' }}</td>
        <td>
            @foreach($pembelian_bahan_baku->bahan_bakus as $item)
            <i class="bi bi-dot"></i>{{ $item->nama_bahan_baku }} ({{ $item->pivot->jumlah }} {{$item->satuan}} x Rp.{{ number_format ($item->pivot->harga, 0, '.', '.') }})<br>
            @endforeach
        </td>
        <td>{{$pembelian_bahan_baku->tanggal}}</td>
        <td>Rp.{{ number_format ($pembelian_bahan_baku->bahan_bakus->sum(function($bahan_baku) { return $bahan_baku->pivot->jumlah * $bahan_baku->pivot->harga; }), 0, '.', '.')  }}</td>
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




<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

<div class="row mb-3">
    <h1>Dapoer Van Kriwil</h1>
    <h5>Jl. Mekar Harum No.34</h5>
    <h6>Mekar Wangi, Bojongloa Kidul, Kota Bandung, Jawa Barat.</h6><br><br>
    <h3><b><center>Laporan Waste Menu</center></b></h3>
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

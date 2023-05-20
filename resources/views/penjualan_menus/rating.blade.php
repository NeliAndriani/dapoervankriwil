@extends('layouts.app')

@section('content')
@php
    $selectedMenus = $penjualan_menus->menus->pluck('menu_id')->toArray();
    $selectedRatings = $penjualan_menus->menus->pluck('pivot.rating_menu')->toArray();
@endphp

<!-- Kode HTML lainnya -->

<h2><b><center>Rating Menu</center></b></h2><br>
<form action="{{ route('penjualan_menus.ubahRating', ['penjualan_menu' => $penjualan_menus->penjualan_menu_id]) }}" method="POST" enctype="multipart/form-data">
    @method('PATCH')
    @csrf
    <table class="table" id="menus_table">
        <thead>
            <tr>
                <th>Menu</th>
                <th>Rating Menu</th>
            </tr>
        </thead>
        <tbody>
            @for ($index = 0; $index < count($selectedMenus); $index++)
                @php
                    $menu_id = $selectedMenus[$index];
                    $menu = $menus->find($menu_id);
                    $rating = $selectedRatings[$index];
                @endphp
                <tr id="menu{{ $index }}">
                    <td>
                        {{ $menu->nama_menu }}
                    </td>
                    <td>
                        <input type="number" name="ratings[]" class="form-control rating-input @error('ratings.' . $index) is-invalid @enderror"
                            value="{{ old('ratings.' . $index, $rating) }}" min="0" max="5"/>
                        @error('ratings.' . $index)
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </td>
                </tr>
            @endfor
        </tbody>
    </table>
    <br>
    <div class="row">
        <div class="col-md-6">
            <input class="btn btn-success btn-md btn-block " type="submit" value="Simpan">
        </div>
        <div class="col-md-6">
            <input class="btn btn-danger btn-md btn-block " type="reset" value="Batal">
        </div>
    </div>
</form>
@endsection

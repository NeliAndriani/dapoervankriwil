@extends('layouts.app')

@section('content')
@php
    $selectedMenus = $penjualan_menus->menus->pluck('menu_id')->toArray();
    $selectedQuantities = $penjualan_menus->menus->pluck('pivot.jumlah')->toArray();
    $selectedRatings = $penjualan_menus->menus->pluck('pivot.rating_menu')->toArray();
@endphp

<!-- Kode HTML lainnya -->

<h2><b><center>Ubah Penjualan Menu</center></b></h2><br>
<form action="{{ route('penjualan_menus.update', ['penjualan_menu' => $penjualan_menus->penjualan_menu_id]) }}" method="POST" enctype="multipart/form-data">
    @method('PATCH')
    @csrf
            <table class="table" id="menus_table">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($index = 0; $index < count($selectedMenus); $index++)
                    @php
                        $menu_id = $selectedMenus[$index];
                        $menu = $menus->find($menu_id);
                        $quantity = $selectedQuantities[$index];
                        $rating = $selectedRatings[$index];
                    @endphp
                    <tr id="menu{{ $index }}">
                        <td>
                            <select name="menus[]" class="form-control menu-select @error('menus.' . $index) is-invalid @enderror">
                                <option value="">-- Pilih menu --</option>
                                @foreach ($menus as $menuOption)
                                    <option value="{{ $menuOption->menu_id }}" data-harga="{{ $menuOption->harga }}" data-stok="{{$menuOption->stok}}"
                                        @if ($menuOption->menu_id == $menu_id)
                                            selected
                                        @endif
                                    >
                                        {{ $menuOption->nama_menu }} (Rp.{{ number_format( $menuOption->harga, 0, '.', '.') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('menus.' . $index)
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </td>
                        <td>
                            <input type="number" name="quantities[]" class="form-control quantity-input @error('quantities.' . $index) is-invalid @enderror"
                                value="{{ old('quantities.' . $index, $quantity) }}" min="0" />
                            @error('quantities.' . $index)
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </td>
                        <td><span class="subtotal">Rp.0</span></td>
                    </tr>
                @endfor

                    <tr id="menu {{$index}}"></tr>
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-12">
                    <button id="add_row" class="btn btn-primary pull-left">+ Tambah baris menu</button>
                    <button id='delete_row' class="pull-right btn btn-danger">- Kurangi baris menu</button>
                </div>
            </div>
            <br>
            <div class="form-group">
                <div class="col-sm">
                <label for="tanggal">Tanggal:</label>
                <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal', $penjualan_menus->tanggal) }}" readonly>
                @error('tanggal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                </div>
            </div>
            @error('metode_bayar')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <div class="form-group">
            <label for="Metode_bayar" class="col-sm-2 col-form-label">Metode Bayar: </label>
            <div class="col-sm-3">
                <select class="form-select form-select-md" name="metode_bayar" style="width: 280px;">
                    <option selected>Pilih metode pembayaran</option>
                    <option value="0" @if ($penjualan_menus->metode_bayar == 0) selected @endif>Tunai</option>
                    <option value="1" @if ($penjualan_menus->metode_bayar == 1) selected @endif>QRIS</option>
                </select>
            </div>
            </div>
            <div>
                <strong>Total Harga:</strong> <span class="badge text-bg-success" id="total_harga" style="font-size: 14px;">Rp.0</span>
            </div>
            <br><br>
            <div class="row">
                <div class="col-md-6">
                    <input class="btn btn-success btn-md btn-block " type="submit" value="Ubah">
                </div>
                <div class="col-md-6">
                    <input class="btn btn-danger btn-md btn-block " type="reset" value="Batal">
                </div>
            </div>
</form>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
       $(document).ready(function() {
        let row_number = 1;

        function calculateSubtotal(row) {
            let harga = parseInt(row.find('.menu-select option:selected').data('harga'));
            let jumlah = parseInt(row.find('.quantity-input').val());
            if (!isNaN(harga) && !isNaN(jumlah)) {
                let subtotal = harga * jumlah;
                row.find('.subtotal').text('Rp.' + subtotal + '.00');
            }
        }

        function calculateTotal() {
            let total = 0;
            $('#menus_table tbody tr').each(function() {
                let harga = parseInt($(this).find('.menu-select option:selected').data('harga'));
                let jumlah = parseInt($(this).find('.quantity-input').val());
                if (!isNaN(harga) && !isNaN(jumlah)) {
                    let subtotal = harga * jumlah;
                    $(this).find('.subtotal').text('Rp.' + subtotal.toLocaleString('id-ID'));
                    total += subtotal;
                }
            });
            $('#total_harga').text('Rp.' + total.toLocaleString('id-ID'));
        }

        function validateStock(row) {
        let menuSelect = row.find('.menu-select');
        let quantityInput = row.find('.quantity-input');
        let selectedOption = menuSelect.find('option:selected');
        let availableStock = parseInt(selectedOption.data('stok'));
        let quantity = parseInt(quantityInput.val());

        if (!isNaN(availableStock) && !isNaN(quantity)) {
        //     let maxQuantity = Math.min(quantity, availableStock); // Batasi nilai quantity sesuai stok maksimal
        //     quantityInput.val(maxQuantity); // Set nilai input field sesuai nilai yang dibatasi
         }

        if (quantity > availableStock) {
            let errorMessage = 'Jumlah melebihi stok yang tersedia (Stok: ' + availableStock + ')';
            quantityInput.addClass('is-invalid');
            quantityInput.siblings('.stock-error').remove(); // Hapus pesan peringatan yang ada sebelumnya
            quantityInput.after('<div class="invalid-feedback stock-error">' + errorMessage + '</div>');
        } else {
            quantityInput.removeClass('is-invalid');
            quantityInput.siblings('.stock-error').remove();
        }
    }


        $("#add_row").click(function(e) {
            e.preventDefault();
            let new_row_number = row_number;
            let new_row = '<tr id="menu' + new_row_number + '">';
            new_row += '<td>';
            new_row += '<select name="menus[]" class="form-control menu-select">';
            new_row += '<option value="">-- Pilih menu --</option>';
            @foreach ($menus as $menu)
            new_row +=
                '<option value="{{ $menu->menu_id }}" data-harga="{{ $menu->harga }}" data-stok="{{ $menu->stok }}">{{ $menu->nama_menu }} (Rp{{ $menu->harga }})</option>';
            @endforeach
            new_row += '</select>';
            new_row += '</td>';
            new_row += '<td><input type="number" name="quantities[]" class="form-control quantity-input" /></td>';
            new_row += '<td><input type="number" name="ratings[]" class="form-control rating-input"/></td>';
            new_row += '<td><span class="subtotal">Rp0</span></td>'; // Add subtotal column
            new_row += '</tr>';
            $('#menus_table tbody').append(new_row);
            row_number++;

            // Attach change event handler to the new row
            $('#menu' + new_row_number).find('.menu-select').change(function() {
                calculateSubtotal($(this).closest('tr'));
                validateStock($(this).closest('tr'));
                calculateTotal();
            });
            $('#menu' + new_row_number).find('.quantity-input').on('input', function() {
                calculateSubtotal($(this).closest('tr'));
                validateStock($(this).closest('tr'));
                calculateTotal();
            });
        });

        $("#delete_row").click(function(e) {
            e.preventDefault();
            if (row_number > 1) {
                $('#menus_table tbody tr:last-child').remove();
                row_number--;
            }

            calculateTotal(); // Recalculate total after row deletion
        });

        // Attach change event handler to existing rows
        $(document).on('change', '.menu-select', function() {
            calculateSubtotal($(this).closest('tr'));
            validateStock($(this).closest('tr'));
            calculateTotal();
        });
        $(document).on('input', '.quantity-input', function() {
            calculateSubtotal($(this).closest('tr'));
            validateStock($(this).closest('tr'));
            calculateTotal();
        });

        function calculateKembalian(totalBayar, totalHarga) {
            let kembalian = totalBayar - totalHarga;
            $('#kembalian').text('Rp.' + kembalian.toLocaleString('id-ID'));
        }

        $('#total_bayar').on('input', function() {
            let totalBayar = parseInt($(this).val());
            let totalHarga = parseInt($('#total_harga').text().replace('Rp.', '').replace(/\D/g, ''));
            if (!isNaN(totalBayar) && !isNaN(totalHarga)) {
                calculateKembalian(totalBayar, totalHarga);
            }
        });

        // Disable total bayar input if metode bayar is QRIS
        $('select[name="metode_bayar"]').on('change', function() {
            let metodeBayar = $(this).val();
            let totalBayarInput = $('#total_bayar');

            if (metodeBayar === '1') {
                totalBayarInput.val('');
                totalBayarInput.prop('disabled', true);
                calculateKembalian(0, 0);
            } else {
                totalBayarInput.prop('disabled', false);
            }
        });

    });
</script>

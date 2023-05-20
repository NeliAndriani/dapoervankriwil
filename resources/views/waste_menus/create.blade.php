@extends('layouts.app')

@section('content')
<h2><b><center>Tambah Waste Menu</center></b></h2><br>
<form action="{{ route('waste_menus.store') }}" method="POST">
    @csrf
            <table class="table" id="menus_table">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="menu0">
                        <td>
                            <select name="menus[]" class="form-control menu-select">
                                <option value="">-- Pilih menu --</option>
                                @foreach ($menus as $menu)
                                    <option value="{{ $menu->menu_id }}" data-harga="{{ $menu->harga }}" data-stok="{{$menu->stok}}">
                                        {{ $menu->nama_menu }} (Rp{{ $menu->harga }})
                                    </option>
                                @endforeach
                            </select>
                            {{-- @error('nama_menu')
                            <span class="is-invalid-feedback" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </span>
                            @enderror --}}
                        </td>
                        <td>
                            <input type="number" name="quantities[]" class="form-control quantity-input @error('quantities') is-invalid @enderror" min="0" oninput="this.value = Math.abs(this.value)"/>
                        </td>
                        @error('quantities.*')
                        <span class="is-invalid-feedback" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </span>
                        @enderror
                    </tr>
                    <tr id="menu1"></tr>
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-12">
                    <button id="add_row" class="btn btn-primary pull-left">+ Tambah baris menu</button>
                    <button id='delete_row' class="pull-right btn btn-danger">- Hapus baris menu</button>
                </div>
            </div>
            <br>
            <div class="form-group">
                <div class="col-sm">
                <label for="tanggal">Tanggal:</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ date('Y-m-d') }}" readonly>
                </div>
            </div>
            <br><br>
            <div class="row">
                <div class="col-md-12">
                <input class="btn btn-success btn-md btn-block " type="submit" value="Simpan">
            </div>
            </div>
</form>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let row_number = 1;

        function validateStock(row) {
        let menuSelect = row.find('.menu-select');
        let quantityInput = row.find('.quantity-input');
        let selectedOption = menuSelect.find('option:selected');
        let availableStock = parseInt(selectedOption.data('stok'));
        let quantity = parseInt(quantityInput.val());

        if (!isNaN(availableStock) && !isNaN(quantity)) {
            let maxQuantity = Math.min(quantity, availableStock); // Batasi nilai quantity sesuai stok maksimal
            quantityInput.val(maxQuantity); // Set nilai input field sesuai nilai yang dibatasi
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
            new_row += '<td><span class="subtotal">Rp0</span></td>'; // Add subtotal column
            new_row += '</tr>';
            $('#menus_table tbody').append(new_row);
            row_number++;

            // Attach change event handler to the new row
            $('#menu' + new_row_number).find('.menu-select').change(function() {
                validateStock($(this).closest('tr'));
            });
            $('#menu' + new_row_number).find('.quantity-input').on('input', function() {
                validateStock($(this).closest('tr'));
            });
        });

        $("#delete_row").click(function(e) {
            e.preventDefault();
            if (row_number > 1) {
                $('#menus_table tbody tr:last-child').remove();
                row_number--;
            }

           });

        // Attach change event handler to existing rows
        $(document).on('change', '.menu-select', function() {
            validateStock($(this).closest('tr'));
        });

        $(document).on('input', '.quantity-input', function() {
            validateStock($(this).closest('tr'));
        });

    });
</script>

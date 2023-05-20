@extends('layouts.app')

@section('content')
<h2><b><center>Tambah Pembelian Bahan Baku</center></b></h2><br>
<form action="{{ route('pembelian_bahan_bakus.store') }}" method="POST">
    @csrf
            <table class="table" id="bahan_bakus_table">
                <thead>
                    <tr>
                        <th>Bahan Baku</th>
                        <th>Jumlah</th>
                        <th>Harga per Satuan</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="bahan_baku0">
                        <td>
                            <select name="bahan_bakus[]" class="form-control menu-select">
                                <option value="">-- Pilih bahan baku --</option>
                                @foreach ($bahan_bakus as $bahan_baku)
                                    <option value="{{ $bahan_baku->bahan_baku_id }}">
                                        {{ $bahan_baku->nama_bahan_baku }} (/{{ $bahan_baku->satuan }})
                                    </option>
                                @endforeach
                            </select>
                            {{-- @error('bahan_bakus.*')
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
                        <td>
                            <input type="number" name="harga[]" class="form-control harga-input @error('harga') is-invalid @enderror" min="0" oninput="this.value = Math.abs(this.value)" />
                        </td>
                        @error('harga.*')
                        <span class="is-invalid-feedback" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </span>
                        @enderror
                        <td><span class="subtotal">Rp.0</span></td>
                    </tr>
                    <tr id="bahan_baku1"></tr>
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-12">
                    <button id="add_row" class="btn btn-primary pull-left">+ Tambah baris bahan baku</button>
                    <button id='delete_row' class="pull-right btn btn-danger">- Hapus baris bahan baku</button>
                </div>
            </div>
            <br>
            <div class="form-group">
                <div class="col-sm">
                <label for="tanggal">Tanggal:</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ date('Y-m-d') }}" readonly>
                </div>
            </div>
            <div>
                <strong>Total Harga:</strong> <span class="badge text-bg-success" id="total_harga" style="font-size: 14px;">Rp.0</span>
            </div>
            <br><br>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let row_number = 1;

        function calculateSubtotal(row) {
        let harga = parseInt(row.find('.harga-input').val());
        let jumlah = parseInt(row.find('.quantity-input').val());
        if (!isNaN(harga) && !isNaN(jumlah)) {
            let subtotal = harga * jumlah;
            row.find('.subtotal').text('Rp.' + subtotal.toLocaleString('id-ID'));
            }
        }

        function calculateTotal() {
            let total = 0;
            $('#bahan_bakus_table tbody tr').each(function() {
                let harga = parseInt($(this).find('.harga-input').val());
                let jumlah = parseInt($(this).find('.quantity-input').val());
                if (!isNaN(harga) && !isNaN(jumlah)) {
                    let subtotal = harga * jumlah;
                    $(this).find('.subtotal').text('Rp.' + subtotal.toLocaleString('id-ID'));
                    total += subtotal;
                }
            });
            $('#total_harga').text('Rp.' + total.toLocaleString('id-ID'));
        }


        $("#add_row").click(function(e) {
            e.preventDefault();
            let new_row_number = row_number;
            let new_row = '<tr id="bahan_baku' + new_row_number + '">';
            new_row += '<td>';
            new_row += '<select name="bahan_bakus[]" class="form-control menu-select">';
            new_row += '<option value="">-- Pilih bahan baku --</option>';
            @foreach ($bahan_bakus as $bahan_baku)
            new_row +=
                '<option value="{{ $bahan_baku->bahan_baku_id }}">{{ $bahan_baku->nama_bahan_baku }}(/{{ $bahan_baku->satuan }})</option>';
            @endforeach
            new_row += '</select>';
            new_row += '</td>';
            new_row += '<td><input type="number" name="quantities[]" class="form-control quantity-input"/></td>';
            new_row += '<td><input type="number" name="harga[]" class="form-control harga-input" /></td>';
            new_row += '<td><span class="subtotal">Rp0</span></td>'; // Add subtotal column
            new_row += '</tr>';
            $('#bahan_bakus_table tbody').append(new_row);
            row_number++;

            // Attach change event handler to the new row
            $('#bahan_baku' + new_row_number).find('.menu-select').change(function() {
                calculateSubtotal($(this).closest('tr'));
                calculateTotal();
            });
            $('#bahan_baku' + new_row_number).find('.quantity-input').on('input', function() {
                calculateSubtotal($(this).closest('tr'));
                calculateTotal();
            });
        });

        $("#delete_row").click(function(e) {
            e.preventDefault();
            if (row_number > 1) {
                $('#bahan_bakus_table tbody tr:last-child').remove();
                row_number--;
            }

            calculateTotal(); // Recalculate total after row deletion
        });

        // Attach change event handler to existing rows
        $(document).on('input', '.harga-input', function() {
            calculateSubtotal($(this).closest('tr'));
            calculateTotal();
        });
        $(document).on('input', '.quantity-input', function() {
            calculateSubtotal($(this).closest('tr'));
            calculateTotal();
        });

    });
</script>

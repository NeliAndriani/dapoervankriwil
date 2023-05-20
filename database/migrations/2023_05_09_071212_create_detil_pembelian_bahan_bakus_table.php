<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetilPembelianBahanBakusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detil_pembelian_bahan_bakus', function (Blueprint $table) {
            $table->char('pembelian_bahan_baku_id', 12);
            $table->char('bahan_baku_id', 7);
            $table->integer('jumlah');
            $table->decimal('harga', 8, 2);

            $table->primary(['pembelian_bahan_baku_id', 'bahan_baku_id'])->index('pk_detil_pembelian_bahan_bakus');

            $table->foreign('pembelian_bahan_baku_id')->references('pembelian_bahan_baku_id')->on('pembelian_bahan_bakus')->onDelete('cascade');
            $table->foreign('bahan_baku_id')->references('bahan_baku_id')->on('bahan_bakus')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detil_pembelian_bahan_bakus');
    }
}

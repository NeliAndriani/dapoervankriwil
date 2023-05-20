<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetilPenjualanMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detil_penjualan_menus', function (Blueprint $table) {
            $table->char('penjualan_menu_id', 12);
            $table->char('menu_id', 7);
            $table->integer('jumlah');
            $table->decimal('harga', 8, 2);
            $table->decimal('rating_menu', 3, 2)->nullable();

            $table->primary(['penjualan_menu_id', 'menu_id'])->index('pk_detil_penjualan_menus');

            $table->foreign('penjualan_menu_id')->references('penjualan_menu_id')->on('penjualan_menus')->onDelete('cascade');
            $table->foreign('menu_id')->references('menu_id')->on('menus')->onDelete('cascade');

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
        Schema::dropIfExists('detil_penjualan_menus');
    }
}

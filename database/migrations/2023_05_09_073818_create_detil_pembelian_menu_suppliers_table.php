<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetilPembelianMenuSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detil_pembelian_menu_suppliers', function (Blueprint $table) {
            $table->char('pembelian_menu_supplier_id', 12);
            $table->char('menu_id', 7);
            $table->integer('jumlah');
            $table->decimal('harga', 8, 2);

            $table->primary(['pembelian_menu_supplier_id', 'menu_id'])->index('pk_detil_pembelian_menu_suppliers');

            $table->foreign('pembelian_menu_supplier_id')->references('pembelian_menu_supplier_id')->on('pembelian_menu_suppliers')->onDelete('cascade')->index('fk_detil_pembelian_menu_suppliers_pembelian_menu_supplier');
            $table->foreign('menu_id')->references('menu_id')->on('menus')->onDelete('cascade')->index('fk_detil_pembelian_menu_suppliers_menu');
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
        Schema::dropIfExists('detil_pembelian_menu_suppliers');
    }
}

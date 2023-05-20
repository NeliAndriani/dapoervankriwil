<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetilWasteMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detil_waste_menus', function (Blueprint $table) {
            $table->char('waste_menu_id', 12);
            $table->char('menu_id', 7);
            $table->integer('jumlah');

            $table->primary(['waste_menu_id', 'menu_id'])->index('pk_detil_waste_menus');

            $table->foreign('waste_menu_id')->references('waste_menu_id')->on('waste_menus')->onDelete('cascade');
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
        Schema::dropIfExists('detil_waste_menus');
    }
}

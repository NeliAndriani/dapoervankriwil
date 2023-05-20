<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PenjualanMenuController;
use App\Http\Controllers\PembelianBahanBakuController;
use App\Http\Controllers\WasteMenuController;
use App\Http\Controllers\PembelianMenuSupplierController;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();




Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('menus', MenuController::class);
Route::get('/menus', [MenuController::class,'index'])->name('menus.index');
Route::get('/menus/create', [MenuController::class,'create'])->name('menus.create');
Route::post('/menus', [MenuController::class,'store'])->name('menus.store');
Route::get('/menus/{menu}', [MenuController::class,'show'])->name('menus.show');
Route::get('/menus/{menu}/edit', [MenuController::class,'edit'])->name('menus.edit');
Route::patch('/menus/{menu}', [MenuController::class,'update'])->name('menus.update');
Route::delete('/menus/{menu}', [MenuController::class,'destroy'])->name('menus.destroy');

Route::resource('bahan_bakus', BahanBakuController::class);
Route::get('/bahan_bakus', [BahanBakuController::class,'index'])->name('bahan_bakus.index');
Route::get('/bahan_bakus/create', [BahanBakuController::class,'create'])->name('bahan_bakus.create');
Route::post('/bahan_bakus', [BahanBakuController::class,'store'])->name('bahan_bakus.store');
Route::get('/bahan_bakus/{bahan_baku}', [BahanBakuController::class,'show'])->name('bahan_bakus.show');
Route::get('/bahan_bakus/{bahan_baku}/edit', [BahanBakuController::class,'edit'])->name('bahan_bakus.edit');
Route::patch('/bahan_bakus/{bahan_baku}', [BahanBakuController::class,'update'])->name('bahan_bakus.update');
Route::delete('/bahan_bakus/{bahan_baku}', [BahanBakuController::class,'destroy'])->name('bahan_bakus.destroy');

Route::resource('suppliers', SupplierController::class);
Route::get('/suppliers', [SupplierController::class,'index'])->name('suppliers.index');
Route::get('/suppliers/create', [SupplierController::class,'create'])->name('suppliers.create');
Route::post('/suppliers', [SupplierController::class,'store'])->name('suppliers.store');
Route::get('/suppliers/{supplier}', [SupplierController::class,'show'])->name('suppliers.show');
Route::get('/suppliers/{supplier}/edit', [SupplierController::class,'edit'])->name('suppliers.edit');
Route::patch('/suppliers/{supplier}', [SupplierController::class,'update'])->name('suppliers.update');
Route::delete('/suppliers/{supplier}', [SupplierController::class,'destroy'])->name('suppliers.destroy');

Route::resource('penjualan_menus', PenjualanMenuController::class);
Route::get('/penjualan_menus', [PenjualanMenuController::class,'index'])->name('penjualan_menus.index');
Route::get('/penjualan_menus/create', [PenjualanMenuController::class,'create'])->name('penjualan_menus.create');
Route::post('/penjualan_menus', [PenjualanMenuController::class,'store'])->name('penjualan_menus.store');
Route::get('/penjualan_menus/{penjualan_menu}', [PenjualanMenuController::class,'show'])->name('penjualan_menus.show');
Route::get('/penjualan_menus/{penjualan_menu}/edit', [PenjualanMenuController::class,'edit'])->name('penjualan_menus.edit');

Route::delete('/penjualan_menus/{penjualan_menu}', [PenjualanMenuController::class,'destroy'])->name('penjualan_menus.destroy');


Route::resource('pembelian_bahan_bakus', PembelianBahanBakuController::class);
Route::get('/pembelian_bahan_bakus', [PembelianBahanBakuController::class,'index'])->name('pembelian_bahan_bakus.index');
Route::get('/pembelian_bahan_bakus/create', [PembelianBahanBakuController::class,'create'])->name('pembelian_bahan_bakus.create');
Route::post('/pembelian_bahan_bakus', [PembelianBahanBakuController::class,'store'])->name('pembelian_bahan_bakus.store');
Route::get('/pembelian_bahan_bakus/{pembelian_bahan_baku}', [PembelianBahanBakuController::class,'show'])->name('pembelian_bahan_bakus.show');
Route::get('/pembelian_bahan_bakus/{pembelian_bahan_baku}/edit', [PembelianBahanBakuController::class,'edit'])->name('pembelian_bahan_bakus.edit');
Route::patch('/pembelian_bahan_bakus/{pembelian_bahan_baku}', [PembelianBahanBakuController::class,'update'])->name('pembelian_bahan_bakus.update');
Route::delete('/pembelian_bahan_bakus/{pembelian_bahan_baku}', [PembelianBahanBakuController::class,'destroy'])->name('pembelian_bahan_bakus.destroy');

Route::resource('waste_menus', WasteMenuController::class);
Route::get('/waste_menus', [WasteMenuController::class,'index'])->name('waste_menus.index');
Route::get('/waste_menus/create', [WasteMenuController::class,'create'])->name('waste_menus.create');
Route::post('/waste_menus', [WasteMenuController::class,'store'])->name('waste_menus.store');
Route::get('/waste_menus/{waste_menu}', [WasteMenuController::class,'show'])->name('waste_menus.show');
Route::get('/waste_menus/{waste_menu}/edit', [WasteMenuController::class,'edit'])->name('waste_menus.edit');
Route::patch('/waste_menus/{waste_menu}', [WasteMenuController::class,'update'])->name('waste_menus.update');
Route::delete('/waste_menus/{waste_menu}', [WasteMenuController::class,'destroy'])->name('waste_menus.destroy');

Route::resource('pembelian_menu_suppliers', PembelianMenuSupplierController::class);
Route::get('/pembelian_menu_suppliers', [PembelianMenuSupplierController::class,'index'])->name('pembelian_menu_suppliers.index');
Route::get('/pembelian_menu_suppliers/create', [PembelianMenuSupplierController::class,'create'])->name('pembelian_menu_suppliers.create');
Route::post('/pembelian_menu_suppliers', [PembelianMenuSupplierController::class,'store'])->name('pembelian_menu_suppliers.store');
Route::get('/pembelian_menu_suppliers/{pembelian_menu_supplier}', [PembelianMenuSupplierController::class,'show'])->name('pembelian_menu_suppliers.show');
Route::get('/pembelian_menu_suppliers/{pembelianMenuSupplier}/edit', [PembelianMenuSupplierController::class,'edit'])->name('pembelian_menu_suppliers.edit');
Route::patch('/pembelian_menu_suppliers/{pembelian_menu_supplier}', [PembelianMenuSupplierController::class,'update'])->name('pembelian_menu_suppliers.update');
Route::delete('/pembelian_menu_suppliers/{pembelian_menu_supplier}', [PembelianMenuSupplierController::class,'destroy'])->name('pembelian_menu_suppliers.destroy');


Route::get('/laporan_penjualan_menus', [PenjualanMenuController::class, 'laporan'])->name('penjualan_menus.laporan');
Route::get('/laporan_pembelian_bahan_bakus', [PembelianBahanBakuController::class, 'laporan'])->name('pembelian_bahan_bakus.laporan');
Route::get('/laporan_pembelian_menu_suppliers', [PembelianMenuSupplierController::class, 'laporan'])->name('pembelian_menu_suppliers.laporan');
Route::get('/laporan_waste_menus', [WasteMenuController::class, 'laporan'])->name('waste_menus.laporan');
Route::get('/laporan_rating_menus', [PenjualanMenuController::class, 'laporan_rating'])->name('rating_menus.laporan');


Route::get('/laporan_penjualan_menus_pdf', [PenjualanMenuController::class, 'downloadpdf'])->name('penjualan_menus.downloadpdf');
Route::get('/laporan_pembelian_bahan_bakus_pdf', [PembelianBahanBakuController::class, 'downloadpdf'])->name('pembelian_bahan_bakus.downloadpdf');
Route::get('/laporan_pembelian_menu_suppliers_pdf', [PembelianMenuSupplierController::class, 'downloadpdf'])->name('pembelian_menu_suppliers.downloadpdf');
Route::get('/laporan_waste_menus_pdf', [WasteMenuController::class, 'downloadpdf'])->name('waste_menus.downloadpdf');
Route::get('/laporan_rating_menus_pdf', [PenjualanMenuController::class, 'downloadpdf_rating'])->name('penjualan_menus.downloadpdf_rating');

Route::get('/penjualan_menus/{penjualan_menu}/rating', [PenjualanMenuController::class,'edit_rating'])->name('penjualan_menus.rating');
Route::patch('/penjualan_menus/{penjualan_menu}/ubahRating', [PenjualanMenuController::class, 'ubahRating'])->name('penjualan_menus.ubahRating');
Route::patch('/penjualan_menus/{penjualan_menu}', [PenjualanMenuController::class,'update'])->name('penjualan_menus.update');

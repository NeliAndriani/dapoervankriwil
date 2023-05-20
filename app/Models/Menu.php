<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    public $incrementing = false;

    protected $table = 'menus';
    protected $primaryKey = 'menu_id';
    protected $fillable = ['menu_id','nama_menu','harga', 'stok', 'deskripsi', 'gambar'];

    public function penjualanMenus()
    {
        return $this->belongsToMany(PenjualanMenu::class, 'detil_penjualan_menus', 'menu_id', 'penjualan_menu_id')
                    ->withPivot('jumlah');
    }

    public function pembelianMenuSuppliers()
    {
        return $this->belongsToMany(PembelianMenuSupplier::class, 'detil_pembelian_menu_suppliers', 'menu_id', 'pembelian_menu_supplier_id')
                    ->withPivot('jumlah');
    }

    public function detil_penjualan_menus()
{
    return $this->hasMany(DetilPenjualanMenu::class, 'menu_id', 'menu_id');
}

}

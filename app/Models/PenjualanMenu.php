<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanMenu extends Model
{
    use HasFactory;
    public $incrementing = false;

    protected $table = 'penjualan_menus';
    protected $primaryKey = 'penjualan_menu_id';
    protected $fillable = ['penjualan_menu_id', 'tanggal', 'metode_bayar'];

    public function penjualan_menu()
    {

        return $this->belongsTo(PenjualanMenu::class);
    }
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'detil_penjualan_menus', 'penjualan_menu_id', 'menu_id')
                    ->withPivot('jumlah', 'harga', 'rating_menu');
    }

    public function detil_penjualan_menus()
{
    return $this->hasMany(DetilPenjualanMenu::class, 'penjualan_menu_id');
}

}


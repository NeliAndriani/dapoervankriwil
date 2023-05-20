<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianMenuSupplier extends Model
{
    use HasFactory;
    public $incrementing = false;

    protected $table = 'pembelian_menu_suppliers';
    protected $primaryKey = 'pembelian_menu_supplier_id';
    protected $fillable = ['pembelian_menu_supplier_id', 'supplier_id', 'tanggal'];

    public function pembelian_menu_supplier()
    {

        return $this->belongsTo(PembelianMenuSupplier::class);
    }
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'detil_pembelian_menu_suppliers', 'pembelian_menu_supplier_id', 'menu_id')
                    ->withPivot('jumlah', 'harga');
    }
    public function suppliers()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

}

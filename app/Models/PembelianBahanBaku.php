<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianBahanBaku extends Model
{
    use HasFactory;
    public $incrementing = false;

    protected $table = 'pembelian_bahan_bakus';
    protected $primaryKey = 'pembelian_bahan_baku_id';
    protected $fillable = ['pembelian_bahan_baku_id', 'tanggal'];

    public function pembelian_bahan_baku()
    {

        return $this->belongsTo(PembelianBahanBaku::class);
    }
    public function bahan_bakus()
    {
        return $this->belongsToMany(BahanBaku::class, 'detil_pembelian_bahan_bakus', 'pembelian_bahan_baku_id', 'bahan_baku_id')
                    ->withPivot('jumlah', 'harga');
    }
}

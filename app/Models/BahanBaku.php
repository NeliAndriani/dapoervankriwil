<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    use HasFactory;
    public $incrementing = false;

    protected $table = 'bahan_bakus';
    protected $primaryKey = 'bahan_baku_id';
    protected $fillable = ['bahan_baku_id','nama_bahan_baku','harga', 'stok', 'satuan', 'deskripsi', 'gambar'];
}

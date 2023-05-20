<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    public $incrementing = false;

    protected $table = 'suppliers';
    protected $primaryKey = 'supplier_id';
    protected $fillable = ['supplier_id','nama_supplier','no_telepon', 'alamat'];
}

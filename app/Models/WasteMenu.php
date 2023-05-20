<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteMenu extends Model
{
    use HasFactory;
    public $incrementing = false;

    protected $table = 'waste_menus';
    protected $primaryKey = 'waste_menu_id';
    protected $fillable = ['waste_menu_id', 'tanggal'];

    public function waste_menu()
    {

        return $this->belongsTo(WasteMenu::class);
    }
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'detil_waste_menus', 'waste_menu_id', 'menu_id')
                    ->withPivot('jumlah');
    }
}

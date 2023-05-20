<?php

namespace App\Http\Controllers;

use App\Models\PenjualanMenu;
use App\Models\Menu;
use App\Models\DetilPenjualanMenu;
use Illuminate\Http\Request;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class PenjualanMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = Menu::paginate(2);
        $penjualan_menus = PenjualanMenu::paginate(2);

        return view('penjualan_menus.index', compact('menus', 'penjualan_menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menus = Menu::all();
        return view('penjualan_menus.create', compact('menus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'nama_menu' => 'required|not_in:0',
            'metode_bayar' => 'required|not_in:0',
            'quantities.*' => 'required',
        ]);

        // Kode penjualan menu
        $kodePenjualanMenu = 'PJ';

        // Tanggal hari ini dalam format ddmmyyyy
        $tanggalHariIni = Carbon::now()->format('ymd');

        // Nomor urut transaksi dengan padding 3 digit angka 0 di depan
        $nomorUrut = \Str::padLeft(PenjualanMenu::select('penjualan_menu_id')->count() + 1, 3, '0');

        // Gabungkan semua komponen untuk mendapatkan id transaksi lengkap
        $id = $kodePenjualanMenu . $tanggalHariIni . $nomorUrut;

        $penjualan_menus = PenjualanMenu::create([
            'penjualan_menu_id' => $id,
            'tanggal' => $request->tanggal,
            'metode_bayar' => $request->metode_bayar
        ]);

        $menus = $request->menus;
        $quantities = $request->quantities;
        $ratings = $request->ratings;

        for ($i = 0; $i < count($menus); $i++) {
            $menu = Menu::find($menus[$i]); // Ambil objek Menu berdasarkan menu_id
            $harga = $menu->harga; // Ambil nilai harga dari objek Menu
            $jumlah = $quantities[$i]; // Ambil nilai jumlah dari input

            // Update stok menu
            $menu->stok -= $jumlah;
            $menu->save();

            if (isset($ratings[$i])) {
                $rating = $ratings[$i];
            } else {
                $rating = null; // Atau berikan nilai default yang sesuai
            }

            $penjualan_menus->menus()->attach([$menus[$i] => [
                'jumlah' => $quantities[$i],
                'rating_menu' => $rating,
                'harga' => $harga,
            ]]);
        }

        $request->session()->flash('success', "Penjualan menu berhasil!");
        return redirect()->route('penjualan_menus.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PenjualanMenu  $penjualanMenu
     * @return \Illuminate\Http\Response
     */
    public function show(PenjualanMenu $penjualanMenu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PenjualanMenu  $penjualanMenu
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $penjualan_menus = PenjualanMenu::findOrFail($id);
        $menus = Menu::all();

        return view('penjualan_menus.edit', compact('penjualan_menus', 'menus'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PenjualanMenu  $penjualanMenu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
{
    $penjualan_menu = PenjualanMenu::findOrFail($id);

    $penjualan_menu->tanggal = $request->tanggal;
    $penjualan_menu->metode_bayar = $request->metode_bayar;
    $penjualan_menu->save();

    $menus = $request->menus;
    $quantities = $request->quantities;

    // Hapus semua relasi menu pada penjualan_menu
    $penjualan_menu->menus()->detach();

    for ($i = 0; $i < count($menus); $i++) {
        $menu = Menu::find($menus[$i]); // Ambil objek Menu berdasarkan menu_id
        $harga = $menu->harga; // Ambil nilai harga dari objek Menu
        $oldQuantity = 0;

        // Cek apakah menu tersebut sudah ada pada penjualan_menu sebelumnya
        if ($penjualan_menu->menus->contains($menu->id)) {
            $oldQuantity = $penjualan_menu->menus->find($menu->id)->pivot->jumlah;
        }

        $penjualan_menu->menus()->attach([$menus[$i] => [
            'jumlah' => $quantities[$i],
            'harga' => $harga,
        ]]);

        $newQuantity = $quantities[$i];
        $stokDifference = $newQuantity - $oldQuantity;

        // Periksa apakah terjadi penambahan atau pengurangan stok
        if ($stokDifference > 0) {
            // Penambahan stok
            DB::table('menus')
                ->where('menu_id', $menu->id)
                ->increment('stok', $stokDifference);
        } elseif ($stokDifference < 0) {
            // Pengurangan stok
            DB::table('menus')
                ->where('menu_id', $menu->id)
                ->decrement('stok', abs($stokDifference));
        }
    }

    $request->session()->flash('success', "Penjualan menu berhasil diubah!");
    return redirect()->route('penjualan_menus.index');
}



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PenjualanMenu  $penjualanMenu
     * @return \Illuminate\Http\Response
     */
    public function destroy(PenjualanMenu $penjualanMenu)
    {
        //
    }

    public function laporan(Request $request)
    {
        $menus = Menu::all();
        $penjualan_menus = PenjualanMenu::all();

        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        if ($tanggalAwal && $tanggalAkhir) {
            $penjualan_menus = PenjualanMenu::query()
                ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                ->get();
        }

        return view('laporans.penjualan_menus', compact('menus', 'penjualan_menus', 'tanggalAwal', 'tanggalAkhir'));
    }

    public function downloadpdf(Request $request)
    {
        $menus = Menu::all();
        $penjualan_menus = PenjualanMenu::all();

        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');
        $penjualan_menus = PenjualanMenu::whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])->get();

        // Hitung total omzet
        $totalOmzet = $penjualan_menus->sum(function($penjualan_menu) {
        return $penjualan_menu->menus->sum(function($menu) {
            return $menu->pivot->jumlah * $menu->harga;
            });
        });



        $pdf = PDF::loadView('laporans.penjualan_menus_pdf', compact('menus', 'penjualan_menus', 'totalOmzet', 'tanggalAwal', 'tanggalAkhir'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('laporan_penjualan_menus.pdf');
    }

    public function laporan_rating(Request $request)
{
    $menus = Menu::with('detil_penjualan_menus')->get();
    $query = Menu::query();

    $ratingMin = $request->input('rating_min');
    $ratingMax = $request->input('rating_max');

    if ($ratingMin !== null && $ratingMax !== null) {
        $menus = $menus->filter(function ($menu) use ($ratingMin, $ratingMax) {
            $averageRating = $menu->detil_penjualan_menus->avg('rating_menu');
            return $averageRating >= $ratingMin && $averageRating <= $ratingMax;
        });
    }

    return view('laporans.rating_menus', compact('menus', 'ratingMin', 'ratingMax'));
}


    public function downloadpdf_rating(Request $request)
    {
        $menus = Menu::with('detil_penjualan_menus')->get();

        $ratingMin = $request->input('rating_min');
        $ratingMax = $request->input('rating_max');

        if ($ratingMin !== null && $ratingMax !== null) {
            $menus = $menus->filter(function ($menu) use ($ratingMin, $ratingMax) {
                $averageRating = $menu->detil_penjualan_menus->avg('rating_menu');
                return $averageRating >= $ratingMin && $averageRating <= $ratingMax;
            });
        }

        $pdf = PDF::loadView('laporans.rating_menus_pdf', compact('menus', 'ratingMin', 'ratingMax'));
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream('laporan_rating_menus.pdf');
    }

    public function edit_rating($id)
    {
        $penjualan_menus = PenjualanMenu::findOrFail($id);
        $menus = Menu::all();

        return view('penjualan_menus.rating', compact('penjualan_menus', 'menus'));
    }

    public function ubahRating(Request $request, $id)
{
    $penjualan_menu = PenjualanMenu::findOrFail($id);

    $ratings = $request->ratings;

    $penjualan_menu->menus()->syncWithoutDetaching(
        collect($ratings)->mapWithKeys(function ($rating, $menu_id) use ($penjualan_menu) {
            $existingMenu = $penjualan_menu->menus->where('menu_id', $menu_id)->first();

            if ($existingMenu) {
                $pivotData = $existingMenu->pivot;
                $pivotData->rating_menu = $rating;
                $existingMenu->pivot = $pivotData;

                return [$existingMenu->getKey() => $pivotData->toArray()];
            } else {
                return [Menu::findOrFail($menu_id)->getKey() => ['rating_menu' => $rating]];
            }
        })
    );

    $request->session()->flash('success', "Rating menu berhasil ditambahkan!");
    return redirect()->route('penjualan_menus.index');
}



    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:pemilik|kasir')->only(['index', 'create', 'store', 'laporan']);
        $this->middleware('role:pemilik')->only(['edit', 'update', 'destroy']);
    }

}

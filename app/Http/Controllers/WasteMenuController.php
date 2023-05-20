<?php

namespace App\Http\Controllers;

use App\Models\WasteMenu;
use App\Models\Menu;
use Illuminate\Http\Request;
use PDF;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;

class WasteMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = Menu::paginate(2);
        $waste_menus = WasteMenu::paginate(2);

        return view('waste_menus.index', compact('menus', 'waste_menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menus = Menu::all();
        return view('waste_menus.create', compact('menus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $validateData = $request->validate([
        //     'nama_menu' => 'required|not_in:0',
        //     'quantities.*' => 'required',
        // ]);
        // Kode penjualan menu
        $kodeWasteMenu = 'WM';

        // Tanggal hari ini dalam format ddmmyyyy
        $tanggalHariIni = Carbon::now()->format('dmy');

        // Nomor urut transaksi dengan padding 3 digit angka 0 di depan
        $nomorUrut = \Str::padLeft(WasteMenu::select('waste_menu_id')->count() + 1, 3, '0');

        // Gabungkan semua komponen untuk mendapatkan id transaksi lengkap
        $id = $kodeWasteMenu . $tanggalHariIni . $nomorUrut;

        $waste_menus = WasteMenu::create([
            'waste_menu_id' => $id,
            'tanggal' => $request->tanggal,
        ]);

        $menus = $request->menus;
        $quantities = $request->quantities;

        for ($i = 0; $i < count($menus); $i++) {
            $menu = Menu::find($menus[$i]); // Ambil objek Menu berdasarkan menu_id
            $jumlah = $quantities[$i]; // Ambil nilai jumlah dari input

            // Update stok menu
            $menu->stok -= $jumlah;
            $menu->save();

            $waste_menus->menus()->attach([$menus[$i] => [
                'jumlah' => $quantities[$i],
            ]]);
        }

        $request->session()->flash('success', "Waste menu berhasil!");
        return redirect()->route('waste_menus.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WasteMenu  $wasteMenu
     * @return \Illuminate\Http\Response
     */
    public function show(WasteMenu $wasteMenu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WasteMenu  $wasteMenu
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $waste_menus = WasteMenu::findOrFail($id);
        $menus = Menu::all();

        return view('waste_menus.edit', compact('waste_menus', 'menus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WasteMenu  $wasteMenu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $waste_menu = WasteMenu::findOrFail($id);

        $waste_menu->tanggal = $request->tanggal;
        $waste_menu->save();

        $menus = $request->menus;
        $quantities = $request->quantities;

        // Hapus semua relasi menu pada penjualan_menu
        $waste_menu->menus()->detach();

        for ($i = 0; $i < count($menus); $i++) {
            $menu = Menu::find($menus[$i]); // Ambil objek Menu berdasarkan menu_id

            $waste_menu->menus()->attach([$menus[$i] => [
                'jumlah' => $quantities[$i],

            ]]);
        }

        $request->session()->flash('success', "Waste menu berhasil diubah!");
        return redirect()->route('waste_menus.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WasteMenu  $wasteMenu
     * @return \Illuminate\Http\Response
     */
    public function destroy(WasteMenu $wasteMenu)
    {
        //
    }

    public function laporan(Request $request)
    {
        $menus = Menu::all();
        $waste_menus = WasteMenu::all();

        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        if ($tanggalAwal && $tanggalAkhir) {
            $waste_menus = WasteMenu::query()
                ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                ->get();
        }

        return view('laporans.waste_menus', compact('menus', 'waste_menus', 'tanggalAwal', 'tanggalAkhir'));
    }

    public function downloadpdf(Request $request)
    {
        $menus = Menu::all();
        $waste_menus = WasteMenu::all();

        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');
        $waste_menus= WasteMenu::whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])->get();


        $pdf = PDF::loadView('laporans.waste_menus_pdf', compact('menus', 'waste_menus', 'tanggalAwal', 'tanggalAkhir'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('laporan_waste_menus.pdf');
    }

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:pemilik|koki')->only(['index', 'create', 'store', 'laporan']);
        $this->middleware('role:pemilik')->only(['edit', 'update', 'destroy']);
    }
}

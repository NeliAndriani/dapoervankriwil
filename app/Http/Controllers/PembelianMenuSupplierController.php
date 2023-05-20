<?php

namespace App\Http\Controllers;

use App\Models\PembelianMenuSupplier;
use App\Models\Menu;
use App\Models\Supplier;
use Illuminate\Http\Request;
use PDF;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;

class PembelianMenuSupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = Menu::paginate(2);
        $suppliers = Supplier::paginate(2);
        $pembelian_menu_suppliers = PembelianMenuSupplier::paginate(2);

        return view('pembelian_menu_suppliers.index', compact('menus', 'pembelian_menu_suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menus = Menu::all();
        $suppliers = Supplier::all();
        return view('pembelian_menu_suppliers.create', compact('menus', 'suppliers'));
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
        //     'harga.*' => 'required',
        //     'nama_supplier' => 'required|not_in:0',
        // ]);
        // Kode penjualan menu
        $kodePembelianMenuSupplier = 'PM';

        // Tanggal hari ini dalam format ddmmyyyy
        $tanggalHariIni = Carbon::now()->format('dmy');

        // Nomor urut transaksi dengan padding 3 digit angka 0 di depan
        $nomorUrut = \Str::padLeft(PembelianMenuSupplier::select('pembelian_menu_supplier_id')->count() + 1, 3, '0');

        // Gabungkan semua komponen untuk mendapatkan id transaksi lengkap
        $id = $kodePembelianMenuSupplier . $tanggalHariIni . $nomorUrut;

        $pembelian_menu_suppliers = PembelianMenuSupplier::create([
            'pembelian_menu_supplier_id' => $id,
            'supplier_id' => $request->suppliers[0],
            'tanggal' => $request->tanggal
        ]);

        $menus = $request->menus;
        $quantities = $request->quantities;
        $harga = $request->harga;

        for ($i = 0; $i < count($menus); $i++) {
            $menu = Menu::find($menus[$i]); // Ambil objek Menu berdasarkan menu_id
            $jumlah = $quantities[$i]; // Ambil nilai jumlah dari input

            // Update stok menu
            $menu->stok += $jumlah;
            $menu->save();

            $pembelian_menu_suppliers->menus()->attach([$menus[$i] => [
                'jumlah' => $quantities[$i],
                'harga' => $harga[$i],
            ]]);
        }

        $request->session()->flash('success', "Pembelian menu supplier berhasil!");
        return redirect()->route('pembelian_menu_suppliers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PembelianMenuSupplier  $pembelianMenuSupplier
     * @return \Illuminate\Http\Response
     */
    public function show(PembelianMenuSupplier $pembelianMenuSupplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PembelianMenuSupplier  $pembelianMenuSupplier
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pembelian_menu_suppliers = PembelianMenuSupplier::findOrFail($id);
        $menus = Menu::all();
        $suppliers = Supplier::all();

        return view('pembelian_menu_suppliers.edit', compact('pembelian_menu_suppliers', 'menus', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PembelianMenuSupplier  $pembelianMenuSupplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pembelian_menu_supplier = PembelianMenuSupplier::findOrFail($id);

        $pembelian_menu_supplier->tanggal = $request->tanggal;
        $pembelian_menu_supplier->save();

        $menus = $request->menus;
        $quantities = $request->quantities;
        $hargas = $request->hargas;

        // Hapus semua relasi menu pada penjualan_menu
        $pembelian_menu_supplier->menus()->detach();

        for ($i = 0; $i < count($menus); $i++) {
            $menu = Menu::find($menus[$i]); // Ambil objek Menu berdasarkan menu_id
            $harga = $hargas[$i]; // Ambil nilai harga dari objek Menu

            $pembelian_menu_supplier->menus()->attach([$menus[$i] => [
                'jumlah' => $quantities[$i],
                'harga' => $harga
            ]]);
        }

        $request->session()->flash('success', "Pembelian menu supplier berhasil diubah!");
        return redirect()->route('pembelian_menu_suppliers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PembelianMenuSupplier  $pembelianMenuSupplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(PembelianMenuSupplier $pembelianMenuSupplier)
    {
        //
    }

    public function laporan(Request $request)
    {
        $menus = Menu::all();
        $suppliers = Supplier::all();
        $pembelian_menu_suppliers = PembelianMenuSupplier::with('suppliers')->get();

        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        if ($tanggalAwal && $tanggalAkhir) {
            $penmbelian_menu_suppliers = PenjualanMenu::query()
                ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                ->get();
        }

        return view('laporans.pembelian_menu_suppliers', compact('menus', 'pembelian_menu_suppliers', 'tanggalAwal', 'tanggalAkhir'));
    }

    public function downloadpdf(Request $request)
    {
        $menus = Menu::all();
        $pembelian_menu_suppliers = PembelianMenuSupplier::all();

        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');
        $pembelian_menu_suppliers = PembelianMenuSupplier::whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])->get();

        // Hitung total omzet
        $totalOmzet = $pembelian_menu_suppliers->sum(function($pembelian_menu_supplier) {
        return $pembelian_menu_suppliers->menus->sum(function($menu) {
            return $menu->pivot->jumlah * $menu->pivot->harga;
            });
        });



        $pdf = PDF::loadView('laporans.pembelian_menu_suppliers_pdf', compact('menus', 'pembelian_menu_suppliers', 'totalOmzet', 'tanggalAwal', 'tanggalAkhir'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('laporan_pembelian_menu_suppliers.pdf');
    }

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:pemilik|staff_pembelian')->only(['index', 'create', 'store', 'laporan']);
        $this->middleware('role:pemilik')->only(['edit', 'update', 'destroy']);
    }
}

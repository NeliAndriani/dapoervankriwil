<?php

namespace App\Http\Controllers;

use App\Models\PembelianBahanBaku;
use App\Models\BahanBaku;
use Illuminate\Http\Request;
use PDF;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;

class PembelianBahanBakuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bahan_bakus = BahanBaku::paginate(2);
        $pembelian_bahan_bakus = PembelianBahanBaku::paginate(2);

        return view('pembelian_bahan_bakus.index', compact('bahan_bakus', 'pembelian_bahan_bakus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bahan_bakus = BahanBaku::all();
        return view('pembelian_bahan_bakus.create', compact('bahan_bakus'));
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
        //     'nama_bahan_baku' => 'required|not_in:0',
        //     'quantities.*' => 'required',
        //     'harga.*' => 'required',
        // ]);

        // Kode penjualan menu
        $kodePembelianBahanBaku = 'PB';

        // Tanggal hari ini dalam format ddmmyyyy
        $tanggalHariIni = Carbon::now()->format('dmy');

        // Nomor urut transaksi dengan padding 3 digit angka 0 di depan
        $nomorUrut = \Str::padLeft(PembelianBahanBaku::select('pembelian_bahan_baku_id')->count() + 1, 3, '0');

        // Gabungkan semua komponen untuk mendapatkan id transaksi lengkap
        $id = $kodePembelianBahanBaku . $tanggalHariIni . $nomorUrut;


        $pembelian_bahan_bakus = PembelianBahanBaku::create([
            'pembelian_bahan_baku_id' => $id,
            'tanggal' => $request->tanggal,
        ]);

        $bahan_bakus = $request->bahan_bakus;
        $quantities = $request->quantities;
        $harga = $request->harga;

        for ($i = 0; $i < count($bahan_bakus); $i++) {
            $bahan_baku = BahanBaku::find($bahan_bakus[$i]); // Ambil objek Menu berdasarkan menu_id
            $jumlah = $quantities[$i]; // Ambil nilai jumlah dari input
            // Update stok menu
            $bahan_baku->stok += $jumlah;
            $bahan_baku->save();

            $pembelian_bahan_bakus->bahan_bakus()->attach([$bahan_bakus[$i] => [
                'jumlah' => $quantities[$i],
                'harga' => $harga[$i],
            ]]);
        }

        $request->session()->flash('success', "Pembelian Bahan Baku berhasil!");
        return redirect()->route('pembelian_bahan_bakus.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PembelianBahanBaku  $pembelianBahanBaku
     * @return \Illuminate\Http\Response
     */
    public function show(PembelianBahanBaku $pembelianBahanBaku)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PembelianBahanBaku  $pembelianBahanBaku
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pembelian_bahan_bakus = PembelianBahanBaku::findOrFail($id);
        $bahan_bakus = BahanBaku::all();

        return view('pembelian_bahan_bakus.edit', compact('pembelian_bahan_bakus', 'bahan_bakus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PembelianBahanBaku  $pembelianBahanBaku
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pembelian_bahan_baku = PembelianBahanBaku::findOrFail($id);

        $pembelian_bahan_baku->tanggal = $request->tanggal;
        $pembelian_bahan_baku->save();

        $bahan_bakus = $request->bahan_bakus;
        $quantities = $request->quantities;
        $hargas = $request->hargas;

        // Hapus semua relasi menu pada penjualan_menu
        $pembelian_bahan_baku->bahan_bakus()->detach();

        for ($i = 0; $i < count($bahan_bakus); $i++) {
            $bahan_baku = BahanBaku::find($bahan_bakus[$i]); // Ambil objek Menu berdasarkan menu_id
            $harga = $hargas[$i]; // Ambil nilai harga dari objek Menu

            $pembelian_bahan_baku->bahan_bakus()->attach([$bahan_bakus[$i] => [
                'jumlah' => $quantities[$i],
                'harga' => $harga
            ]]);
        }

        $request->session()->flash('success', "Pembelian bahan baku berhasil diubah!");
        return redirect()->route('pembelian_bahan_bakus.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PembelianBahanBaku  $pembelianBahanBaku
     * @return \Illuminate\Http\Response
     */
    public function destroy(PembelianBahanBaku $pembelianBahanBaku)
    {
        //
    }

    public function laporan(Request $request)
    {
        $bahan_bakus = BahanBaku::all();
        $pembelian_bahan_bakus = PembelianBahanBaku::all();

        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        if ($tanggalAwal && $tanggalAkhir) {
            $pembelian_bahan_bakus = PembelianBahanBaku::query()
                ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                ->get();
        }

        return view('laporans.pembelian_bahan_bakus', compact('bahan_bakus', 'pembelian_bahan_bakus', 'tanggalAwal', 'tanggalAkhir'));
    }


    public function downloadpdf(Request $request)
    {
        $bahan_bakus = BahanBaku::all();
        $pembelian_bahan_bakus = PembelianBahanBaku::all();

        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');
        $pembelian_bahan_bakus = PembelianBahanBaku::whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])->get();

        // Hitung total omzet
        $totalOmzet = $pembelian_bahan_bakus->sum(function($pembelian_bahan_baku) {
        return $pembelian_bahan_bakus->bahan_bakus->sum(function($bahan_baku) {
            return $bahan_baku->pivot->jumlah * $bahan_baku->pivot->harga;
            });
        });



        $pdf = PDF::loadView('laporans.pembelian_bahan_bakus_pdf', compact('bahan_bakus', 'pembelian_bahan_bakus', 'totalOmzet', 'tanggalAwal', 'tanggalAkhir'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('laporan_pembelian_bahan_bakus.pdf');
    }

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:pemilik|staff_pembelian')->only(['index', 'create', 'store', 'laporan']);
        $this->middleware('role:pemilik')->only(['edit', 'update', 'destroy']);
    }
}

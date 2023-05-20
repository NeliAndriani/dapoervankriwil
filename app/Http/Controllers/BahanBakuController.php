<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class BahanBakuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bahan_bakus = BahanBaku::paginate(2);
        return view('bahan_bakus.index', compact('bahan_bakus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bahan_bakus.create');
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
            'nama_bahan_baku' => 'required|max:30',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|max:5',
            'deskripsi' => 'max:100',
            'gambar' => 'required|file|image|max:5000',
        ]);

        $bahan_bakuCount = BahanBaku::where('bahan_baku_id', 'like', 'BAKU%')->count();
        $id = 'BAKU' . \Str::padLeft($bahan_bakuCount + 1, 3, '0');

        // Cek keunikan bahan_baku_id
        while (BahanBaku::where('bahan_baku_id', $id)->exists()) {
            $bahan_bakuCount++;
            $id = 'BAKU' . \Str::padLeft($bahan_bakuCount + 1, 3, '0');
        }

        $fileExtension = $request->gambar->getClientOriginalExtension();
        $fileRename = "bakuimg-" . time() . ".{$fileExtension}";
        $request->gambar->storeAs('public', $fileRename);

        $bahan_baku = BahanBaku::create([
            'bahan_baku_id' => $id,
            'nama_bahan_baku' => $request->nama_bahan_baku,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'satuan' => $request->satuan,
            'deskripsi' => $request->deskripsi,
            'gambar' => $fileRename
        ]);

        $request->session()->flash('success', "Berhasil menambahkan {$validateData['nama_bahan_baku']}!");
        return redirect()->route('bahan_bakus.index');
    }

    public function imageUploadTesting(Request $request)
    {
        if ($request->hasFile('gambar')) {
            echo "Path: " . $request->gambar->path() . '<br>';
            echo "Extension: " . $request->gambar->extension() . '<br>';
            echo "Org. Extension: " . $request->gambar->getClientOriginalExtension() . '<br>';
            echo "MIME Type: " . $request->gambar->getMimeType() . '<br>';
            echo "Org. Filename: " . $request->gambar->getClientOriginalName() . '<br>';
            echo "Size: " . $request->gambar->getSize() . '<br>';
        } else {
            echo "No uploaded file!";
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BahanBaku  $bahanBaku
     * @return \Illuminate\Http\Response
     */
    public function show($bahan_baku_id)
    {
        $bahan_baku = BahanBaku::where('bahan_baku_id', $bahan_baku_id)->first();
        return view('bahan_bakus.show', compact('bahan_baku'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BahanBaku  $bahanBaku
     * @return \Illuminate\Http\Response
     */
    public function edit(BahanBaku $bahan_baku)
    {
        return view('bahan_bakus.edit', compact('bahan_baku'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BahanBaku  $bahanBaku
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BahanBaku $bahan_baku)
    {
        $validateData = $request->validate([
            'nama_bahan_baku' => 'required|max:30',
            'harga' => 'required|min:1',
            'stok' => 'required|min:1',
            'satuan' => 'required|max:5',
            'deskripsi' =>  'max:100',
            'gambar' => 'required|file|image|max:5000',
        ]);

        if($request->gambar){
            \Storage::disk('public')->delete($bahan_baku->gambar);
        }

        $bahan_baku->update($validateData);

        $fileExtension = $request->gambar->getClientOriginalExtension();
        $fileRename = "bakuimg-".time().".{$fileExtension}";
        $request->gambar->storeAs('public', $fileRename);

        $bahan_baku->gambar = $fileRename;
        $bahan_baku->save();

        $request->session()->flash('success', "Berhasil mengubah {$validateData['nama_bahan_baku']}!");
        return redirect()->route('bahan_bakus.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BahanBaku  $bahanBaku
     * @return \Illuminate\Http\Response
     */
    public function destroy(BahanBaku $bahan_baku)
    {
        \Storage::disk('public')->delete($bahan_baku->gambar);
        $bahan_baku->delete();
        return redirect()->route('bahan_bakus.index')->with('success', "Berhasil menghapus {$bahan_baku['nama_bahan_baku']}!");
    }

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:pemilik|staff_pembelian')->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    }
}

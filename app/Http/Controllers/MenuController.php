<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $menus = Menu::paginate(2);

        return view('menus.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('menus.create');
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
            'nama_menu' => 'required|max:30',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'max:100',
            'gambar' => 'required|file|image|max:5000',
        ]);

        $menuCount = Menu::where('menu_id', 'like', 'MENU%')->count();
        $id = 'MENU' . \Str::padLeft($menuCount + 1, 3, '0');

        // Cek keunikan menu_id
        while (Menu::where('menu_id', $id)->exists()) {
            $menuCount++;
            $id = 'MENU' . \Str::padLeft($menuCount + 1, 3, '0');
        }

        $fileExtension = $request->gambar->getClientOriginalExtension();
        $fileRename = "menuimg-" . time() . ".{$fileExtension}";
        $request->gambar->storeAs('public', $fileRename);

        $menu = Menu::create([
            'menu_id' => $id,
            'nama_menu' => $request->nama_menu,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'gambar' => $fileRename
        ]);

        $request->session()->flash('success', "Berhasil menambahkan {$validateData['nama_menu']}!");
        return redirect()->route('menus.index');
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
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show($menu_id)
    {
        $menu = Menu::where('menu_id', $menu_id)->first();
         return view('menus.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        return view('menus.edit', compact('menu'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        $validateData = $request->validate([
            'nama_menu' => 'required|max:30',
            'harga' => 'required|min:1',
            'stok' => 'required|min:1',
            'deskripsi' =>  'max:100',
            'gambar' => 'required|file|image|max:5000',
        ]);

        if($request->gambar){
            \Storage::disk('public')->delete($menu->gambar);
        }

        $menu->update($validateData);

        $fileExtension = $request->gambar->getClientOriginalExtension();
        $fileRename = "menuimg-".time().".{$fileExtension}";
        $request->gambar->storeAs('public', $fileRename);

        $menu->gambar = $fileRename;
        $menu->save();

        $request->session()->flash('success', "Berhasil mengubah {$validateData['nama_menu']}!");
        return redirect()->route('menus.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        \Storage::disk('public')->delete($menu->gambar);
        $menu->delete();
        return redirect()->route('menus.index')->with('success', "Berhasil menghapus {$menu['nama_menu']}!");
    }

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:pemilik|koki|staff_pembelian')->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    }
}

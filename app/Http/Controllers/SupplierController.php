<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Supplier::paginate(2);
        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('suppliers.create');
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
            'nama_supplier' => 'required|max:30',
            'no_telepon' => ['required', 'string', 'regex:/^(\+62|0)[0-9]{9,12}$/'],
            'alamat' => 'required|max:100',
        ]);

        $supplierCount = Supplier::where('supplier_id', 'like', 'SUPP%')->count();
        $id = 'SUPP' . \Str::padLeft($supplierCount + 1, 3, '0');

        // Cek keunikan supplier_id
        while (Supplier::where('supplier_id', $id)->exists()) {
            $supplierCount++;
            $id = 'SUPP' . \Str::padLeft($supplierCount + 1, 3, '0');
        }

        $supplier = Supplier::create([
            'supplier_id' => $id,
            'nama_supplier' => $request->nama_supplier,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat
        ]);

        $request->session()->flash('success', "Berhasil menambahkan {$validateData['nama_supplier']}!");
        return redirect()->route('suppliers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show($supplier_id)
    {
        $supplier = Supplier::where('supplier_id', $supplier_id)->first();
         return view('suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validateData = $request->validate([
            'nama_supplier' => 'required|max:30',
            'no_telepon' => ['required', 'string', 'regex:/^(\+62|0)[0-9]{9,12}$/'],
            'alamat' => 'max:100',
        ]);


        $supplier->update($validateData);

        $request->session()->flash('success', "Berhasil mengubah {$validateData['nama_supplier']}!");
        return redirect()->route('suppliers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', "Berhasil menghapus {$supplier['nama_supplier']}!");
    }

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:pemilik|staff_pembelian')->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    }
}

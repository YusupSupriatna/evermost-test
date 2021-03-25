<?php

namespace App\Http\Controllers;

use App\Model\Barang;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
class BarangController extends BaseController
{
    // Store 
    public function index(){
        $result = Barang::paginate();
        return response()->json($result);
    }

    // Store 
    public function store(Request $request, Barang $barang){
        $this->validate($request,[
            'kode'          => 'required|unique:ref_barang',
            'nama_barang'   => 'required',
            'stok_awal'     => 'required',
            'price'         => 'required',
        ]);

        $barang->fill(request()->all());
        $barang->save();

        return response()->json($barang);
    }

    // Update 
    public function update(Request $request,Barang $barang){
        $this->validate($request,[
            'kode'          => 'required|unique:ref_barang,kode,'.$request->id,
            'nama_barang'   => 'required',
            'stok_awal'     => 'required',
            'price'         => 'required',
        ]);
        $barang = $barang->updates(request());
        return response()->json($barang);
    }

    
    // Destroy
    public function delete(Barang $barang){
        $barang->deletes(request());
        return response()->json(['status'=>true]);

    }
}

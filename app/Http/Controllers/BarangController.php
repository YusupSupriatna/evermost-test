<?php

namespace App\Http\Controllers;

use App\Model\Barang;
use Laravel\Lumen\Routing\Controller as BaseController;

class BarangController extends BaseController
{
    // Store 
    public function index(){
        $result = Barang::paginate();
        return response()->json($result);
    }

    // Store 
    public function store(Barang $barang){
        $barang->fill(request()->all());
        $barang->save();

        return response()->json($barang);
    }

    // Update 
    public function update(Barang $barang){
        $barang = $barang->updates(request());
        return response()->json($barang);
    }

    
    // Destroy
    public function delete(Barang $barang){
        $barang->deletes(request());
    }
}

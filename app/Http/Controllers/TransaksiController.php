<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Barang;
use App\Model\TransaksiOrder;


class TransaksiController extends Controller
{
    
    // Chek Stock
    public function stock($id, $defautl='json'){ // You can use $default variable with input (json, collection) if
        $barang = Barang::with('transaksi')->find($id);
        $data=[
            'record'        => $barang,
            'total_pesanan'   => $barang->transaksi->whereIn('status', ['checkout','bayar'])->sum('jumlah')
        ];
        if($defautl == 'json'){
            // JSON
            return response()->json($data);
        }

        return $data;
    }

    // Chek Out Barang
    public function checkout(){
        $data_json = [];
        foreach(request()->barang_id as $key => $val){
            $cek = $this->stock($val, 'collection');
            $barangReject = ($cek['record']->stock_reject?$cek['record']->stock_reject:0);
            if((($cek['record']->stok_awal - $cek['total_pesanan']) - $barangReject  >=  request()->jumlah[$key])){
                $param = [
                    'barang_id'  => $val,
                    'jumlah'     => request()->jumlah[$key],
                    'status'     => 'checkout',
                    'stock'     => ($cek['record']->stok_awal - $cek['total_pesanan']-$barangReject),
                    'message'    => 'Barang Tersedia Siap di prosses',
                ];
                TransaksiOrder::checkoutSave($param);
                
                $data_json[] = $param;
            }else{
                $data_json[] = [
                    'barang_id'  => $val,
                    'jumlah'     => request()->jumlah[$key],
                    'status'     => 'habis',
                    'stock'     => ($cek['record']->stok_awal - $cek['total_pesanan']-$barangReject),
                    'message'    => 'Barang Habis Tidak Dapat di Prosses',
                ];
            }

        }
        return response()->json($data_json);
    }

    // Bayar Barang
    public function bayar(){
        // $cek = $this->stock(1, 'collection');
        
        // /$cek['total_order']
    }

}
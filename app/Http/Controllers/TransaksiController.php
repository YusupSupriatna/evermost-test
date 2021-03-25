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
        if($barang){
            $data=[
                'record'        => $barang,
                'total_pesanan'   => $barang->transaksi->whereIn('status', ['checkout','bayar'])->sum('jumlah')
            ];
            if($defautl == 'json'){
                // JSON
                return response()->json($data);
            }
    
            return $data;
        }else {
            return false;
        }
    }

    // Chek Out Barang
    public function checkout(){
        $data_json = [];
        foreach(request()->barang_id as $key => $val){
            $cek = $this->stock($val, 'collection');
            if($cek){
                $stockReject =  $cek['record']->stock_reject?$cek['record']->stock_reject:0;
                if((($cek['record']->stok_awal - $cek['total_pesanan'])-$stockReject >=  request()->jumlah[$key])){
                    $param = [
                        'barang_id'  => $val,
                        'jumlah'     => request()->jumlah[$key],
                        'status'     => 'checkout',
                        'stock'      => (($cek['record']->stok_awal - $cek['total_pesanan'])-$stockReject),
                        'message'    => 'Barang Tersedia Siap di prosses',
                    ];
                    TransaksiOrder::checkoutSave($param);
                    
                    $data_json[] = $param;
                }else{
                    $data_json[] = [
                        'barang_id'  => $val,
                        'jumlah'     => request()->jumlah[$key],
                        'status'     => 'habis',
                        'stock'     => (($cek['record']->stok_awal - $cek['total_pesanan'])-$stockReject),
                        'message'    => 'Barang Habis Tidak Dapat di Prosses',
                    ];
                }
            }else{
                $data_json[] = [
                    'barang_id'  => $val,
                    'status' => 'failed',
                    'message' => 'Tidak ditemukan'
                ];
            }

        }
        return response()->json($data_json);
    }

    // Bayar Barang
    public function bayar(){
    
    }

}
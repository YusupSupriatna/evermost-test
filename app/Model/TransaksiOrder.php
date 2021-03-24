<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class TransaksiOrder extends Model
{
    protected $table = 'trans_order';

    protected $fillable = [
        'barang_id',
        'jumlah',
        'status' // checkout, bayar, batal
    ]; 
    // Relation

    // Action
    public static function checkoutSave($param){
        $result = new self;
        $result->fill($param);
        $result = $result->save();
        return $result;
    }
}
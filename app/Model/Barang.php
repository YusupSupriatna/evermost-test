<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use App\Model\TransaksiOrder;

class Barang extends Model
{
    protected $table = 'ref_barang';
    
    protected $fillable = ['kode', 'nama_barang', 'stok_awal', 'price', 'diskon', 'stok_akhir', 'stok_reject'];

    public function transaksi(){
        return $this->hasMany(TransaksiOrder::class, 'barang_id');
    }

    // Action
    public function updates($request){
        $barang = static::find($request->id);
        $barang->fill($request->all());
        $barang->save();

        return $barang;
    }

    public function deletes($request){
        $barang = static::find($request->id);
        $barang->delete();

        return $barang;
    }
}
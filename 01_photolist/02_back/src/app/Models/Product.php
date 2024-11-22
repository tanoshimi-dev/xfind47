<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = '商品'; 

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, '仕入先', '仕入先');
    }

    public function product_vari()
    {
        return $this->hasMany(ProductVari::class, '商品番号', '商品番号');
    }
    // 先頭行のみを取得
    public function firstProductVari()
    {
        return $this->product_vari()->first();
    }

    public function product_net()
    {
        return $this->hasMany(ProductNet::class, '商品番号', '商品番号');
    }

    public function code()
    {
        return $this->hasOne(Code::class, 'コード', '制作担当')
            ->where('コード.コード区分', '=' , '60');
    }

}

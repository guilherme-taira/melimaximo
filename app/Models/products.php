<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class products extends Model
{
    use HasFactory;

    protected $table = 'products';


    public function getId(){
        return $this->attributes['id'];
    }

    public static function getCompleteProduct(){
        $data = DB::table('products')
        ->join('produtos_prazo', 'products.id', '=', 'produtos_prazo.product_id')
        ->get();

     return $data;
    }
}

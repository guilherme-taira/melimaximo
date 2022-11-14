<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orders extends Model
{
    use HasFactory;


    protected $table = 'orders';


    public static function ifExisteOrder(String $Pedido) {
        $data = orders::where('numeroPedido','=',$Pedido)->first();
        return $data;
    }


    public function getId(){
        return $this->attributes['id'];
    }
}

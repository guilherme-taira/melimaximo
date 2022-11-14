<?php

namespace App\Http\Controllers\Hotmart\Services;

use App\Http\Controllers\Controller;
use App\Models\items;
use App\Models\orders;
use App\Models\products;
use App\Models\User;
use Illuminate\Http\Request;

abstract class MethodSave{
    public abstract function saveNewOrder(array $dados);
    public abstract function ifExist(String $pedido):bool;
    public abstract function saveOrder(array $order);
}

class saveNewOrder extends MethodSave
{
    public function saveNewOrder(array $orders){
        try {
            foreach ($orders as $order) {
              if($this->ifExist($order['numeroPedido']) == TRUE){
                echo "JA EXISTE";
                return false;
              }else{
                echo "CADASTRADO COM SUCESSO";
                $this->savePivot($order);
                return true;
              }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function ifExist(String $pedido):bool{
        if(orders::ifExisteOrder($pedido)){
            return true;
        }else{
            return false;
        }
    }

    public function saveOrder(array $order){
        $newOrder = new orders();
        $newOrder->numeroPedido = $order['numeroPedido'];
        $newOrder->total = $order['valor'];
        $newOrder->user_id = $this->saveClient($order);
        $newOrder->save();
        return $newOrder->getId();
    }

    public function saveClient(array $order):int{
        $newUser = new User();
        $newUser->name = $order['name'];
        $newUser->email = $order['email'];
        $newUser->password = bcrypt('35712986');
        $newUser->save();
        return $newUser->getId();
    }


    public function saveProduct(array $order):int {
        $newProduct = new products();
        $newProduct->name = $order['produto'];
        $newProduct->description = $order['produto'];
        $newProduct->price = $order['valor'];
        $newProduct->save();
        return $newProduct->getId();
    }

    public function savePivot(array $order){
        $newPivot = new items();
        $newPivot->quantity = 1;
        $newPivot->price = 1;
        $newPivot->order_id = $this->saveOrder($order);
        $newPivot->product_id = $this->saveProduct($order);
        $newPivot->save();
    }


}

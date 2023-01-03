<?php

namespace App\Http\Controllers\DAO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class saleFeeDAO extends Controller
{
    private float $sale_fee_amount;

    /**
     * Get the value of sale_fee_amount
     */ 
    public function getSale_fee_amount()
    {
        return $this->sale_fee_amount;
    }

    /**
     * Set the value of sale_fee_amount
     *
     * @return  self
     */ 
    public function setSale_fee_amount($sale_fee_amount)
    {
        $this->sale_fee_amount = $sale_fee_amount;

        return $this;
    }
}

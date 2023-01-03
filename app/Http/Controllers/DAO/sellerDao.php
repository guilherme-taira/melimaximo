<?php

namespace App\Http\Controllers\DAO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class sellerDao extends Controller
{
    private $power_seller_status;
    private $empresa;

    /**
     * Get the value of power_seller_status
     */
    public function getPower_seller_status()
    {
        return $this->power_seller_status;
    }

    /**
     * Set the value of power_seller_status
     *
     * @return  self
     */
    public function setPower_seller_status($power_seller_status)
    {
        $this->power_seller_status = $power_seller_status;

        return $this;
    }

    /**
     * Get the value of empresa
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set the value of empresa
     *
     * @return  self
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;

        return $this;
    }
}

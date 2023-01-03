<?php

namespace App\Http\Controllers\SaleFeeAmount;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DAO\saleFeeDAO;
use Illuminate\Http\Request;

abstract class saleFeeAmountImplementacao
{
    private float $price;
    private string $listing_type_id;
    private String $category_id;
    private saleFeeAmountController $tafira;

    public function __construct($price, $listing_type_id, $category_id,saleFeeAmountController $tafira)
    {
        $this->price = $price;
        $this->listing_type_id = $listing_type_id;
        $this->category_id = $category_id;
        $this->tafira = $tafira;
    }

     /**
     * Get the value of price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Get the value of listing_type_id
     */
    public function getListing_type_id()
    {
        return $this->listing_type_id;
    }

    /**
     * Get the value of category_id
     */
    public function getCategory_id()
    {
        return $this->category_id;
    }

    public function getSaleFeeAmount(){
        return $this->saleFeeAmountClass;
    }

    public abstract function getFee(): saleFeeDAO;

    /**
     * Get the value of tafira
     */
    public function getTafira()
    {
        return $this->tafira;
    }

    /**
     * Set the value of tafira
     *
     * @return  self
     */
    public function setTafira($tafira)
    {
        $this->tafira = $tafira;

        return $this;
    }
}

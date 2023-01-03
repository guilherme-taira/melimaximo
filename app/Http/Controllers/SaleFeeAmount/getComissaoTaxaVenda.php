<?php

namespace App\Http\Controllers\SaleFeeAmount;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DAO\saleFeeDAO;
use Illuminate\Http\Request;

class getComissaoTaxaVenda extends saleFeeAmountImplementacao
{
    public function getFee():saleFeeDAO{
        $taxa = $this->getTafira()->getFeeAmount($this->getPrice(),$this->getListing_type_id(),$this->getCategory_id());
        $FeeDao = new saleFeeDAO;
        $FeeDao->setSale_fee_amount($taxa);
        return $FeeDao;
    }
}

<?php

namespace App\Http\Controllers\Hotmart\Services;

use App\Http\Controllers\Cliente\ClienteController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class servicesTrataDadosRequisicaoController extends Controller
{
    private $approved_date;
    private $transaction;
    private ClienteController $cliente;

    public function __construct($approved_date, $transaction, $cliente)
    {
        $this->approved_date = $approved_date;
        $this->transaction = $transaction;
        $this->cliente = $cliente;
    }

    /**
     * Get the value of approved_date
     */
    public function getApproved_date()
    {
        return $this->approved_date;
    }

    /**
     * Set the value of approved_date
     *
     * @return  self
     */
    public function setApproved_date($approved_date)
    {
        $this->approved_date = $approved_date;

        return $this;
    }

    /**
     * Get the value of transaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * Set the value of transaction
     *
     * @return  self
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;

        return $this;
    }
}

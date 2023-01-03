<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class getGtinProduct extends Controller
{
    const ERROR_1 = "N/D";

    public function getAttributeGtin(array $atributtes)
    {
        try {
            foreach ($atributtes as $atributte) {
                if ($atributte->id == 'GTIN') {
                    return isset($atributte->value_name) ? $atributte->value_name : self::ERROR_1;
                }
            }
        } catch (\Exception $e) {
            return self::ERROR_1;
        }

    }

    public function getAttributeBrand(array $atributtes): string
    {
        foreach ($atributtes as $atributte) {
            if ($atributte->id == 'BRAND') {
                return isset($atributte->value_name) ? $atributte->value_name : "N/D";
            }
        }
    }
    public function getAttributeTipoAnuncio(String $atributte): string
    {
        switch ($atributte) {
            case 'gold_special':
                return "CLÁSSICO";
                break;
            case 'gold_pro':
                return "PREMIUM";
                break;
            default:
                # code...
                break;
        }
    }
}

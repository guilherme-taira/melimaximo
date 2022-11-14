<?php

namespace App\Http\Controllers\RequestTemplete;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class requestTemplateController
{
    public abstract function resource();
    public abstract function get($resource);
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rastreio extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'estoque',
        'foto',
        'codigo',
        'preco',
        'dataVerificada'
    ];

    protected $table = 'rastreio';
}

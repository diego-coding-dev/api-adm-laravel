<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Storage extends Model
{
    use HasFactory,
        SoftDeletes;

    /**
     * 
     * @var string Definindo explicitamente a tabela
     */
    public $table = "storages";

    /**
     * 
     * @var array Definindo atributos que são mass assignable
     */
    protected $fillable = [
        'product_id',
        'quantiy',
        'price'
    ];
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;


    /**
     * 
     * @var string Definindo explicitamente a tabela
     */
    public $table = "order_items";

    /**
     * 
     * @var array Definindo atributos que são mass assignable
     */
    protected $fillable = [
        'storage_id',
        'order_id',
        'quantity',
        'total_price',
        'is_finish'
    ];
}
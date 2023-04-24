<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory,
        SoftDeletes;

    /**
     * 
     * @var string Definindo explicitamente a tabela
     */
    public $table = "orders";

    /**
     * 
     * @var array Definindo atributos que são mass assignable
     */
    protected $fillable = [
        'client_id',
        'employee_id',
        'register',
        'total',
        'is_settled',
        'is_canceled'
    ];

    /**
     * Relacionamento entre ORDERS e CLIENTS
     * 
     * @return object
     */
    public function client(): object
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id');
    }
}
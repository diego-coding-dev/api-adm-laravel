<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{

    use HasFactory,
        SoftDeletes;

    /**
     * 
     * @var string Definindo explicitamente a tabela
     */
    public $table = "clients";

    /**
     * 
     * @var array Definindo atributos que são mass assignable
     */
    protected $fillable = [
        'type_user_id',
        'name',
        'email'
    ];

    /**
     * Relacionamento entre CLIENTS e ORDERS
     * 
     * @return object
     */
    public function orders(): object
    {
        return $this->hasMany(\App\Models\Order::class, 'client_id', 'id');
    }

}
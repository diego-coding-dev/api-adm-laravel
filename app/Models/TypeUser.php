<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypeUser extends Model
{

    use HasFactory,
        SoftDeletes;

    /**
     * 
     * @var string Definindo explicitamente a tabela
     */
    public $table = "type_users";

    /**
     * 
     * @var array Definindo atributos que são mass assignable
     */
    protected $fillable = [
        'type_user'
    ];

}
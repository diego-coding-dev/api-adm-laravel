<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendentPassword extends Model
{

    use HasFactory;

    /**
     * 
     * @var string Definindo explicitamente a tabela
     */
    public $table = "pendent_passwords";

    /**
     * 
     * @var array Definindo atributos que são mass assignable
     */
    protected $fillable = [
        'email'
    ];

}

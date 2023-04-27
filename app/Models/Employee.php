<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class Employee extends Authenticatable
{

    use HasFactory,
        HasApiTokens,
        SoftDeletes;

    /**
     * 
     * @var string Definindo explicitamente a tabela
     */
    public $table = "employees";

    /**
     * 
     * @var array Definindo atributos que são mass assignable
     */
    protected $fillable = [
        'type_user_id',
        'name',
        'email',
        'password',
        'is_active'
    ];

    /**
     * Dados que deven estar escondidos de serialização
     * 
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    protected function password(): Attribute
    {
        return Attribute::make(
                        set: fn(string $value) => hash::make($value)
        );
    }

}

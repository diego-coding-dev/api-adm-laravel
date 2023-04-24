<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeAbility extends Model
{

    use HasFactory,
        SoftDeletes;

    /**
     * 
     * @var string Definindo explicitamente a tabela
     */
    public $table = "employee_abilities";

    /**
     * 
     * @var array Definindo atributos que são mass assignable
     */
    protected $fillable = [
        'ability_id',
        'employee_id',
    ];

    /**
     * Relacionamento entre ABILITIES e EMPLOYEE_ABILITIES
     * 
     * @return object
     */
    public function ability(): object
    {
        return $this->belongsTo(\App\Models\Ability::class, 'ability_id');
    }

}
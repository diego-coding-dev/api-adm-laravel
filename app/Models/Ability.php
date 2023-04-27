<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{

    use HasFactory;

    /**
     * 
     * @var string Definindo explicitamente a tabela
     */
    public $table = "abilities";

    /**
     * 
     * @var array Definindo atributos que são mass assignable
     */
    protected $fillable = [
        'ability'
    ];

    /**
     * Relacionamento entre ABILITIES e EMPLOYEE_ABILITIES
     * 
     * @return object
     */
    public function employeeAbilities(): object
    {
        return $this->hasMany(\App\Models\EmployeeAbility::class, 'ability_id', 'id');
    }

    /**
     * Relacionamento entre ABILITIES e TYPE_USER_ABILITIES
     * 
     * @return object
     */
    public function typeUserAbilities(): object
    {
        return $this->hasMany($this->typeUserAbilities::class, 'ability_id', 'id');
    }

}

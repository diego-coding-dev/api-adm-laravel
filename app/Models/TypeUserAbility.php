<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeUserAbility extends Model
{

    use HasFactory;

    /**
     * 
     * @var string Definindo explicitamente a tabela
     */
    public $table = "type_user_abilities";

    /**
     * 
     * @var array Definindo atributos que são mass assignable
     */
    protected $fillable = [
        'type_user_id',
        'ability_id'
    ];
    
    /**
     * Relacionamento entre TYPE_USER_ABILITIES e ABILITIES
     * 
     * @return object
     */
    public function ability(): object
    {
        return $this->belongsTo(\App\Models\Ability::class, 'ability_id');
    }
    
}

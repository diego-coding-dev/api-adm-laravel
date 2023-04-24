<?php

namespace App\Traits;

/**
 * Description of SetAbilities
 *
 * @author diego
 */
Trait SetAbilities
{

    public function formatAbilities(\Illuminate\Database\Eloquent\Collection $abilities): array
    {
        $newAbilities = [];

        foreach ($abilities as $key => $ability) {
            array_push($newAbilities, $ability->ability->ability);
        }

        return $newAbilities;
    }

}

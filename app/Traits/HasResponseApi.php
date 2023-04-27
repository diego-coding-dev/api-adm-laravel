<?php

namespace App\Traits;

/**
 *
 * @author diego
 */
Trait HasResponseApi
{

    private function makeResponse(array $data, int $code, string $status = null)
    {
        return response()->json([
                    $data,
                    'status' => $status
                        ], $code);
    }

}

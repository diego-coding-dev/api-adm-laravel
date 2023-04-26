<?php

namespace App\Http\Middleware;

use App\Models\Product;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use function response;

class HasProduct
{

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $typeProductId = $request->segment(5);

        if (!$produc = Product::where('type_product_id', $typeProductId)->first()) {
            return $next($request);
        }

        return response()->json([
                    'message' => 'categoria possui produtos!'
                        ], 200);
    }

}

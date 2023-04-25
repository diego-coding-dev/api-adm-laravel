<?php

namespace App\Http\Middleware;

use App\Models\Storage;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use function response;

class InStock
{

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $productId = $request->segment(5);
        $storage = Storage::where('product_id', $productId)->first();

        if ($storage->quantity > 0) {
            return response()->json([
                        'message' => 'produto com estoque não vazio!'
            ]);
        }

        return $next($request);
    }

}

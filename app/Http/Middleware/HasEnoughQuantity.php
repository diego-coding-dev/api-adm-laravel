<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasEnoughQuantity
{

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $orderItemId = $request->segment(5);
        $itemCartData = $request->only('quantity');

        $orderItem = \App\Models\OrderItem::find($orderItemId);

        $storage = \App\Models\Storage::find($orderItem->storage_id);

        if ($storage->quantity < $itemCartData['quantity']) {
            return response()->json([
                        'message' => 'não há quantidade suficiente'
            ]);
        }

        return $next($request);
    }

}

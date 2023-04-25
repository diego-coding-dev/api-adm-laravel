<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BeforeAddOrderItem
{

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $itemCartData = $request->only('storage_id', 'quantity');

        if ($orderItem = \App\Models\OrderItem::where('storage_id', $itemCartData['storage_id'])->first()) {
            return response()->json([
                        'message' => 'item já existe'
            ]);
        }

        $storage = \App\Models\Storage::find($itemCartData['storage_id']);
        if ($storage->quantity < $itemCartData['quantity']) {
            return response()->json([
                        'message' => 'não há quantidade suficiente'
            ]);
        }


        return $next($request);
    }

}

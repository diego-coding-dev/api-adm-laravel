<?php

namespace App\Http\Middleware;

use App\Models\OrderItem;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasItemInCart
{

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $orderId = $request->segment(5);

        if (!$orderItem = OrderItem::where('order_id', $orderId)->first()) {
            return response()->json([
                        'message' => 'sem itens no carrinho'
            ]);
        }

        return $next($request);
    }

}

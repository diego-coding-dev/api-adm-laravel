<?php

namespace App\Http\Controllers\Api\Service;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function response;

class OrderController extends Controller
{

    /**
     * Exibe todos os pedidos realizados
     * 
     * @return object
     */
    public function list(): object
    {
        return response()->json([
            'data' => [
                'order_list' => Order::where('is_canceled', false)->paginate(10)
            ]
        ], 200);
    }

    /**
     * Registra um novo pedido
     * 
     * @param Request $request
     * @return object
     */
    public function create(Request $request): object
    {
        // TO-DO: realizar as devidas validações

        $orderData = $request->all();
        $orderData['register'] = bin2hex(random_bytes(5));

        if (!$order = Order::create($orderData)) {
            return response()->json([
                'data' => [],
                'message' => 'operação indisponível!'
            ], 503);
        }

        return response()->json([
            'data' => [
                'order' => $order
            ],
            'message' => 'pedido registrado!',
        ], 200);
    }

    public function show(string $orderId)
    {
        return response()->json([
            'data' => [
                'order' => Order::find($orderId),
                'items_quantity' => DB::table('order_items')->select(DB::raw('count(id) as items_quantity'))->where('order_id', $orderId)->get()
            ],
            'message' => 'encontrado'
        ], 200);
    }

    public function finish(string $orderId)
    {
        DB::beginTransaction();

        try {
            $order = Order::find($orderId);
            $orderItems = OrderItem::where('order_id', $orderId);

            foreach ($orderItems as $item) {
                $item->is_finished = true;
                $item->save();
            }

            $order->is_settled = true;
            $order->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'data' => [],
                'message' => 'operação indisponível!'
            ], 503);
        }

        return response()->json([
            'data' => [],
            'message' => 'pedido processado!'
        ], 200);
    }

    /**
     * Cancela um pedido
     * 
     * @param string $orderId
     * @return object
     */
    public function cancel(string $orderId): object
    {
        $order = Order::find($orderId);
        $orderItems = OrderItem::where('order_id', $orderId)->get();

        DB::beginTransaction();

        try {
            foreach ($orderItems as $item) {
                $storage = Storage::find($item->storage_id);

                $storage->quantity = $storage->quantity + $item->quantity;
                $storage->save();

                $item->delete();
            }

            $order->is_canceled = true;
            $order->save();

            DB::commit();
        } catch (\Exception $exc) {
            DB::rollBack();

            return response()->json([
                'data' => [],
                'message' => 'operação indisponível!'
            ], 503);
        }

        return response()->json([
            'data' => [],
            'message' => 'pedido cancelado!'
        ], 200);
    }

}
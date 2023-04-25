<?php

namespace App\Http\Controllers\Api\Storage;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Storage;
use Illuminate\Http\Request;
use DB;

class OrderItemController extends Controller
{

    /**
     * Exibe todos os itens do pedido
     * 
     * @param string $orderId
     * @return object
     */
    public function list(string $orderId): object
    {
        return response()->json([
                    'data' => [
                        'order_items' => DB::table('order_items_view')->where('order_id', $orderId)->paginate(10)
                    ]
                        ], 200);
    }

    /**
     * Adiciona um item a lista de itens do pedido
     * 
     * @param Request $request
     * @param string $orderId
     * @return object
     */
    public function add(Request $request, string $orderId): object
    {
        // TO-DO: realizar as devidas validações

        $orderItemData = $request->only('storage_id', 'quantity');

        $orderItemData['order_id'] = $orderId;

        $order = Order::find($orderId);
        $storage = Storage::find($orderItemData['storage_id']);

        DB::beginTransaction();

        try {
            $orderItemData['total_price'] = (float) $storage->price * $orderItemData['quantity'];
            OrderItem::create($orderItemData);

            $storage->quantity = $storage->quantity - $orderItemData['quantity'];
            $storage->save();

            $order->total = $order->total + $orderItemData['total_price'];
            $order->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                        'data' => [],
                        'message' => 'operação indisponível!'
                            ], 503);
            //throw $th;
        }

        return response()->json([
                    'data' => [
                        'order_item' => DB::table('order_items_view')->where('order_id', $orderId)->paginate(10)
                    ],
                    'message' => 'item adicionado!',
                        ], 200);
    }

    /**
     * Exibe dados de um item do pedido
     * 
     * @param string $orderItemId
     * @return object
     */
    public function show(string $orderItemId): object
    {
        if (!$orderItem = DB::table('order_items_view')->where('id', $orderItemId)->get()) {
            return response()->json([
                        'data' => [],
                        'message' => 'operação indisponível!'
                            ], 503);
        }

        return response()->json([
                    'data' => ['order_item' => $orderItem]
                        ], 200);
    }

    /**
     * Adiciona quantidade de um item do pedido
     * 
     * @param Request $request
     * @param string $orderItemId
     * @return object
     */
    public function addQuantity(Request $request, string $orderItemId): object
    {
        // TO-DO: realizar as devidas validações

        $orderItemData = $request->only('quantity');
        $orderItem = OrderItem::find($orderItemId);
        $order = Order::find($orderItem->order_id);
        $storage = Storage::find($orderItem->storage_id);

        DB::beginTransaction();

        try {
            $orderItem->quantity = $orderItemData['quantity'] + $orderItem->quantity;
            $orderItem->total_price = (float) $orderItem->quantity * $storage->price;
            $orderItem->save();

            $storage->quantity = $storage->quantity - $orderItemData['quantity'];
            $storage->save();

            $order->total = (float) $order->total + ($orderItemData['quantity'] * $storage->price);
            $order->save();

            DB::commit();
        } catch (\Exception $exc) {
            DB::rollback();

            return response()->json([
                        'data' => [],
                        'message' => 'operação indisponível!'
                            ], 503);
        }

        return response()->json([
                    'data' => ['order_item' => $orderItem],
                    'message' => 'item atualizado!'
                        ], 200);
    }

    /**
     * Remove quantidade de um item do pedido
     * 
     * @param Request $request
     * @param string $orderItemId
     * @return object
     */
    public function removeQuantity(Request $request, string $orderItemId): object
    {
        // TO-DO: realizar as devidas validações

        $orderItemData = $request->only('quantity');
        $orderItem = OrderItem::find($orderItemId);
        $order = Order::find($orderItem->order_id);
        $storage = Storage::find($orderItem->storage_id);

        DB::beginTransaction();

        try {
            $orderItem->quantity = $orderItem->quantity - $orderItemData['quantity'];
            $orderItem->total_price = (float) $orderItem->quantity * $storage->price;
            $orderItem->save();

            $storage->quantity = $storage->quantity + $orderItemData['quantity'];
            $storage->save();

            $order->total = (float) $order->total - ($orderItemData['quantity'] * $storage->price);
            $order->save();

            DB::commit();
        } catch (\Exception $exc) {
            DB::rollback();

            return response()->json([
                        'data' => [],
                        'message' => 'operação indisponível!'
                            ], 503);
        }

        return response()->json([
                    'data' => ['order_item' => $orderItem],
                    'message' => 'item atualizado!'
                        ], 200);
    }

    /**
     * Remove o item do pedido
     * 
     * @param string $orderItemId
     * @return object
     */
    public function remove(string $orderItemId): object
    {
        $orderItem = OrderItem::find($orderItemId);
        $storage = Storage::find($orderItem->storage_id);
        $order = Order::find($orderItem->order_id);

        $order->total = (float) $order->total - $orderItem->total_price;
        $storage->quantity = $storage->quantity + $orderItem->quantity;

        DB::beginTransaction();

        try {
            $orderItem->delete();
            $storage->save();
            $order->save();

            DB::commit();
        } catch (\Exception $exc) {
            DB::rollback();

            return response()->json([
                        'data' => [],
                        'message' => 'operação indisponível!'
                            ], 503);
        }

        return response()->json([
                    'data' => [],
                    'message' => 'item deletedo!'
                        ], 200);
    }

}

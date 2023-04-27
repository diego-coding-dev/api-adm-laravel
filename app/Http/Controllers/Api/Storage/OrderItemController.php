<?php

namespace App\Http\Controllers\Api\Storage;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Storage;
use Illuminate\Http\Request;
use DB;
use \App\Traits\HasResponseApi;

class OrderItemController extends Controller
{

    use HasResponseApi;

    /**
     * Exibe todos os itens do pedido
     * 
     * @param string $orderId
     * @return object
     */
    public function list(string $orderId): object
    {
        $response['data'] = [
            'order_items' => DB::table('order_items_view')->where('order_id', $orderId)->paginate(10)
        ];

        return $this->makeResponse($response, 200, 'found');
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

            $response['data'] = [];

            return $this->makeResponse($response, 500, 'error');
        }

        $response['data'] = [
            'order_item' => DB::table('order_items_view')->where('order_id', $orderId)->paginate(10)
        ];

        return $this->makeResponse($response, 201, 'created');
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
            $response['data'] = [];

            return $this->makeResponse($response, 404, 'not_found');
        }

        $response['data'] = [
            'order_item' => $orderItem
        ];

        return $this->makeResponse($response, 200, 'found');
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

            $response['data'] = [];

            $this->makeResponse($response, 500, 'error');
        }

        $response['data'] = [
            'order_item' => $orderItem
        ];

        return $this->makeResponse($response, 200, 'added');
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

            $response['data'] = [];

            $this->makeResponse($response, 500, 'error');
        }

        $response['data'] = [
            'order_item' => $orderItem
        ];

        return $this->makeResponse($response, 200, 'removed');
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

            $response['data'] = [];

            return $this->makeResponse($response, 500, 'error');
        }

        $response['data'] = [];

        return $this->makeResponse($response, 200, 'deleted');
    }

}

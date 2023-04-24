<?php

namespace App\Http\Controllers\Api\Storage;

use App\Http\Controllers\Controller;
use App\Models\Storage;
use DB;
use Illuminate\Http\Request;

class StorageController extends Controller
{
    /**
     * Retorna os produtos que estão no estoque
     * 
     * @return object
     */
    public function list(): object
    {
        return response()->json([
            'data' => [
                'product_list' => DB::table('storages_view')->paginate(10)
            ]
        ], 200);
    }

    /**
     * Exibe dados de um produto no estoque
     * 
     * @param string $storageId
     * @return object
     */
    public function show(string $storageId): object
    {
        if (!$storage = DB::table('storages_view')->where('deleted_at', null)->find($storageId)) {
            return response()->json([
                'data' => [],
                'message' => 'Produto não encontrado no estoque!'
            ], 200);
        }

        return response()->json([
            'data' => [
                'storage' => $storage
            ],
            'message' => 'encontrado!'
        ], 200);
    }

    /**
     * Adiciona quantidades de um produto no estoque
     * 
     * @param Request $request
     * @param string $storageId
     * @return object
     */
    public function update(Request $request, string $storageId): object
    {
        // TO-DO: realizar as devidas validações

        $storageData = $request->only('quantity', 'price');

        if (!$storage = Storage::find($storageId)) {
            return response()->json([
                'data' => [],
                'message' => 'Produto não encontrado no estoque!'
            ], 200);
        }

        if (array_key_exists('quantity', $storageData)) {
            $storageData['quantity'] += $storage->quantity;
        }

        if (!Storage::where('id', $storageId)->update($storageData)) {
            return response()->json([
                'data' => [],
                'message' => 'operação indisponível!'
            ], 503);
        }

        return response()->json([
            'data' => [
                'product' => Storage::find($storageId)
            ],
            'message' => 'estoque atualizado!'
        ], 200);
    }
}
<?php

namespace App\Http\Controllers\Api\Storage;

use App\Http\Controllers\Controller;
use App\Models\Storage;
use DB;
use Illuminate\Http\Request;
use \App\Traits\HasResponseApi;

class StorageController extends Controller
{

    use HasResponseApi;

    /**
     * Retorna os produtos que estão no estoque
     * 
     * @return object
     */
    public function list(): object
    {
        $response['data'] = [
            'product_list' => DB::table('storages_view')->paginate(10)
        ];

        return $this->makeResponse($response, 200, 'found');
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
            $response['data'] = [];

            return $this->makeResponse($response, 404, 'not_found');
        }

        $response['data'] = [
            'storage' => $storage
        ];

        return $this->makeResponse($response, 200, found);
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
            $response['data'] = [];

            return $this->makeResponse($response, 404, 'not_found');
        }

        if (array_key_exists('quantity', $storageData)) {
            $storageData['quantity'] += $storage->quantity;
        }

        if (!Storage::where('id', $storageId)->update($storageData)) {
            $response['data'] = [];

            return $this->makeResponse($response, 500, 'not_updated');
        }

        $response['data'] = [
            'product' => Storage::find($storageId)
        ];

        return $this->makeResponse($response, 200, 'updated');
    }

}

<?php

namespace App\Http\Controllers\Api\Storage;

use App\Http\Controllers\Controller;
use App\Models\TypeProduct;
use Illuminate\Http\Request;
use \App\Traits\HasResponseApi;

class TypeProductController extends Controller
{

    use HasResponseApi;

    /**
     * Retorna as categorias de produtos cadastradas
     * 
     * @return object
     */
    public function list(): object
    {
        $response['data'] = [
            'type_product_list' => TypeProduct::paginate(10)
        ];

        return $this->makeResponse($response, 200, 'found');
    }

    /**
     * Registra uma nova categoria de produto
     * 
     * @param Request $request
     * @return object
     */
    public function create(Request $request): object
    {
        // TO-DO: realizar as devidas validações

        $typeProductData = $request->all();

        if (!$typeProduct = TypeProduct::create($typeProductData)) {
            $response['data'] = [];

            return $this->makeResponse($response, 500, 'not_created');
        }

        $response['data'] = [
            'type_product' => $typeProduct
        ];

        return $this->makeResponse($response, 201, 'created');
    }

    /**
     * Exibe dados de uma categoria de produto
     * 
     * @param string $typeProductId
     * @return object
     */
    public function show(string $typeProductId): object
    {
        if (!$typeProduct = TypeProduct::find($typeProductId)) {
            $response['data'] = [];

            return $this->makeResponse($response, 404, 'not_found');
        }

        $response['data'] = [
            'type_product' => $typeProduct
        ];

        return $this->makeResponse($response, 200, 'found');
    }

    /**
     * Atualiza dados de uma categoria de produto
     * 
     * @param Request $request
     * @param string $typeProductId
     * @return object
     */
    public function update(Request $request, string $typeProductId): object
    {
        // TO-DO: realizar as devidas validações

        $typeProductData = $request->only('type_product');

        if (!TypeProduct::where('id', $typeProductId)->update($typeProductData)) {
            $response['data'] = [];

            return $this->makeResponse($response, 500, 'not_updated');
        }

        $response['data'] = [
            'type_product_data' => TypeProduct::find($typeProductId)
        ];

        return $this->makeResponse($response, 200, 'updated');
    }

    /**
     * Remove um cliente
     * 
     * @param string $typeProductId
     * @return object
     */
    public function delete(string $typeProductId): object
    {
        if (!TypeProduct::where('id', $typeProductId)->delete()) {
            $response['data'] = [];

            return $this->makeResponse($response, 500, 'error');
        }

        $response['data'] = [];

        return $this->makeResponse($response, 200, 'deleted');
    }

}

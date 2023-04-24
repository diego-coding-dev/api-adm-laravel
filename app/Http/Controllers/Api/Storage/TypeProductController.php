<?php

namespace App\Http\Controllers\Api\Storage;

use App\Http\Controllers\Controller;
use App\Models\TypeProduct;
use Illuminate\Http\Request;

class TypeProductController extends Controller
{

    /**
     * Retorna as categorias de produtos cadastradas
     * 
     * @return object
     */
    public function list(): object
    {
        return response()->json([
                    'data' => [
                        'type_product_list' => TypeProduct::paginate(10)
                    ]
                        ], 200);
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
            return response()->json([
                        'data' => [],
                        'message' => 'operação indisponível!'
                            ], 503);
        }

        return response()->json([
                    'data' => [
                        'type_product' => $typeProduct
                    ],
                    'message' => 'Categoria registrada!',
                        ], 200);
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
            return response()->json([
                        'data' => [],
                        'message' => 'Categoria não encontrada!'
                            ], 200);
        }

        return response()->json([
                    'data' => [
                        'type_product' => $typeProduct
                    ],
                    'message' => 'encontrada!'
                        ], 200);
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
            return response()->json([
                        'data' => [],
                        'message' => 'operação indisponível!'
                            ], 503);
        }

        return response()->json([
                    'data' => [
                        'type_product_data' => TypeProduct::find($typeProductId)
                    ],
                    'message' => 'categoria atualizado!'
                        ], 200);
    }

    /**
     * Remove um cliente
     * 
     * @param Request $request
     * @param string $typeProductId
     * @return object
     */
    public function delete(Request $request, string $typeProductId): object
    {
        if (!TypeProduct::where('id', $typeProductId)->delete()) {
            return response()->json([
                        'data' => [],
                        'message' => 'operação indisponível!'
                            ], 503);
        }

        return response()->json([
                    'data' => [],
                    'message' => 'categoria removida!'
                        ], 200);
    }

}
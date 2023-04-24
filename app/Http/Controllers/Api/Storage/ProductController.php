<?php

namespace App\Http\Controllers\Api\Storage;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Storage as StorageModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;

class ProductController extends Controller
{

    /**
     * Retorna os produtos cadastradas
     * 
     * @return object
     */
    public function list(): object
    {
        return response()->json([
            'data' => [
                'product_list' => Product::paginate(10)
            ]
        ], 200);
    }

    /**
     * Registra um novo produto
     * 
     * @param Request $request
     * @return object
     */
    public function create(Request $request): object
    {
        // TO-DO: realizar as devidas validações

        $productImage = $request->file('image');
        $productData = $request->all();
        $productData['image'] = $productImage->store(null, 'product');

        DB::beginTransaction();

        try {
            $product = Product::create($productData);
            StorageModel::create(['product_id' => $product->id]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Storage::disk('product')->delete($productData['image']);

            return response()->json([
                'data' => [],
                'message' => 'operação indisponível!'
            ], 503);
            // classe transactionException
        }

        return response()->json([
            'data' => [
                'product' => $product
            ],
            'message' => 'produto registrado!',
        ], 200);
    }

    /**
     * Exibe dados de um produto
     * 
     * @param string $productId
     * @return object
     */
    public function show(string $productId): object
    {
        if (!$product = Product::find($productId)) {
            return response()->json([
                'data' => [],
                'message' => 'produto não encontrado!'
            ], 200);
        }

        return response()->json([
            'data' => [
                'product' => $product
            ],
            'message' => 'encontrado!'
        ], 200);
    }

    /**
     * Atualiza imagem do produto
     * 
     * @param Request $request
     * @param string $productId
     * @return object
     */
    public function updateImage(Request $request, string $productId): object
    {
        // TO-DO: realizar as devidas validações

        $product = Product::find($productId);

        $newImage = $request->file('image')->store(null, 'product');

        if (!Product::where('id', $productId)->update(['image' => $newImage])) {
            Storage::disk('product')->delete($newImage);

            return response()->json([
                'data' => [],
                'message' => 'operação indisponível!'
            ], 503);
        }

        Storage::disk('product')->delete($product->image);

        return response()->json([
            'data' => [
                'product' => Product::find($productId)
            ],
            'message' => 'imagem atualizada!'
        ], 200);
    }

    /**
     * Atualiza dados do produto
     * 
     * @param Request $request
     * @param string $productId
     * @return object
     */
    public function update(Request $request, string $productId): object
    {
        // TO-DO: realizar as devidas validações

        $produtData = $request->only('product');

        if (!Product::where('id', $productId)->update($produtData)) {
            return response()->json([
                'data' => [],
                'message' => 'operação indisponível!'
            ], 503);
        }

        return response()->json([
            'data' => [
                'product' => Product::find($productId)
            ],
            'message' => 'produto atualizado!'
        ], 200);
    }

    /**
     * Remove o produto
     * 
     * @param string $produtId
     * @return object
     */
    public function delete(string $productId): object
    {
        DB::beginTransaction();

        try {
            Product::where('id', $productId)->delete();
            StorageModel::where('product_id', $productId)->delete();

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
            'message' => 'produto removido!'
        ], 200);
    }

}
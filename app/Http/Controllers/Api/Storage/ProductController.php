<?php

namespace App\Http\Controllers\Api\Storage;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Storage as StorageModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;
use \App\Traits\HasResponseApi;

class ProductController extends Controller
{

    use HasResponseApi;

    /**
     * Retorna os produtos cadastradas
     * 
     * @return object
     */
    public function list(): object
    {
        $response['data'] = [
            'product_list' => Product::paginate(10)
        ];

        return $this->makeResponse($response, 200, 'found');
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

            $response['data'] = [];

            return $this->makeResponse($response, 500, 'error');
        }

        $response['data'] = [
            'product' => $product
        ];

        return $this->makeResponse($response, 201, 'created');
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
            $response['data'] = [];

            return $this->makeResponse($response, 404, 'not_found');
        }

        $response['data'] = [
            'product' => $product
        ];

        return $this->makeResponse($response, 200, 'found');
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

            $response['data'] = [];

            return $this->makeResponse($response, 500, 'error');
        }

        Storage::disk('product')->delete($product->image);

        $response['data'] = [
            'product' => Product::find($productId)
        ];

        return $this->makeResponse($response, 200, 'updated');
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
            $response['data'] = [];

            return $this->makeResponse($response, 500, 'error');
        }

        $response['data'] = [
            'product' => Product::find($productId)
        ];

        return $this->makeResponse($response, 200, 'updated');
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

            $response['data'] = [];

            return $this->makeResponse($response, 500, 'error');
        }

        $response['data'] = [];

        return $this->makeResponse($response, 200, 'deleted');
    }

}

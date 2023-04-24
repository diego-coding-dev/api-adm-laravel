<?php

namespace App\Http\Controllers\Api\Rh;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{

    /**
     * Retorna os clientes cadastrados
     * 
     * @return object
     */
    public function list(): object
    {
        return response()->json([
                    'data' => [
                        'client_list' => Client::paginate(10)
                    ]
                        ], 200);
    }

    /**
     * Registra um novo cliente
     * 
     * @param Request $request
     * @return object
     */
    public function create(Request $request): object
    {
        // TO-DO: realizar as devidas validações

        $clientData = $request->all();
        $clientData['type_user_id'] = 4;

        if (!$client = Client::create($clientData)) {
            return response()->json([
                        'data' => [],
                        'message' => 'operação indisponível!'
                            ], 503);
        }

        return response()->json([
                    'data' => [
                        'client' => $client
                    ],
                    'message' => 'cliente registrado!',
                        ], 200);
    }

   /**
    * Exibe dados de um cliente
    * 
    * @param string $clientId
    * @return object
    */
    public function show(string $clientId): object
    {
        if (!$client = Client::find($clientId)) {
            return response()->json([
                        'data' => [],
                        'message' => 'client não encontrado!'
                            ], 200);
        }

        return response()->json([
                    'data' => [
                        'client' => $client
                    ],
                    'message' => 'encontrado!'
                        ], 200);
    }

    /**
     * Atualiza dados de um cliente
     * 
     * @param Request $request
     * @param string $clientId
     * @return object
     */
    public function update(Request $request, string $clientId): object
    {
        // TO-DO: realizar as devidas validações

        $clientData = $request->only('email');

        if (!Client::where('id', $clientId)->update($clientData)) {
            return response()->json([
                        'data' => [],
                        'message' => 'operação indisponível!'
                            ], 503);
        }

        return response()->json([
                    'data' => [
                        'client_data' => Client::find($clientId)
                    ],
                    'message' => 'cliente atualizado!'
                        ], 200);
    }

    /**
     * Remove um cliente
     * 
     * @param string $clientId
     * @return object
     */
    public function delete(string $clientId): object
    {
        if (!Client::where('id', $clientId)->delete()) {
            return response()->json([
                        'data' => [],
                        'message' => 'operação indisponível!'
                            ], 503);
        }

        return response()->json([
                    'data' => [],
                    'message' => 'cliente removido!'
                        ], 200);
    }

}
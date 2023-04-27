<?php

namespace App\Http\Controllers\Api\Rh;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use \App\Traits\HasResponseApi;

class ClientController extends Controller
{

    use HasResponseApi;

    /**
     * Retorna os clientes cadastrados
     * 
     * @return object
     */
    public function list(): object
    {
        $reponse['data'] = [
            'clientList' => Client::paginate(10)
        ];

        return $this->makeResponse($reponse, 200, 'found');
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
            $response['data'] = [];

            return $this->makeResponse($response, 500, 'not_created');
        }

        $response['data'] = [
            'client_data' => $client
        ];

        return $this->makeResponse($response, 201, 'created');
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
            $response['data'] = [
                'client' => []
            ];

            return $this->makeResponse($response, 404, 'not_found');
        }

        $response['data'] = [
            'client' => $client
        ];

        return $this->makeResponse($response, 200, 'found');
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
            $response['data'] = [
                'client' => []
            ];

            return $this->makeResponse($response, 500, 'not_updated');
        }

        $response['data'] = [
            'client' => Client::find($clientId)
        ];

        return $this->makeResponse($response, 200, 'updated');
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
            $response['data'] = [
                'client' => []
            ];

            return $this->makeResponse($response, 500, 'error');
        }

        $response['data'] = [
            'client' => []
        ];

        return $this->makeResponse($response, 200, 'deleted');
    }

}

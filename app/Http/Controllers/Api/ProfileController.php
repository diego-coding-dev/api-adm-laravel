<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Traits\HasResponseApi;
use Illuminate\Http\Request;
use function auth;
use function response;

class ProfileController extends Controller
{

    use HasResponseApi;

    public function show()
    {
        $response['data'] = [
            'profile' => auth()->user()
        ];

        return $this->makeResponse($response, 200, 'found');
    }

    public function updateEmail(Request $request)
    {
        // TO-DO: realizar as devidas validações

        $dataForm = $request->only('email');

        if (!Employee::where('id', auth()->user()->id)->update($dataForm)) {
            $reponse['data'] = [];

            return $this->makeResponse($reponse, 500, 'not_updated');
        }
        $reponse['data'] = [
            'profile' => auth()->user()
        ];

        return $this->makeResponse($reponse, 200, 'updated');
    }

    public function updatePassword(Request $request)
    {
       // TO-DO: realizar as devidas validações

        $dataForm = $request->only('password', 'confirm_password');

        if (!Employee::where('id', auth()->user()->id)->update($dataForm)) {
            $reponse['data'] = [];

            return $this->makeResponse($reponse, 500, 'not_updated');
        }
        $reponse['data'] = [
            'profile' => auth()->user()
        ];

        return $this->makeResponse($reponse, 200, 'updated');
    }

}

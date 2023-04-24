<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeAbility;
use \App\Traits\SetAbilities;

class AuthenticationController extends Controller
{

    use SetAbilities;

    /**
     * Realiza a autenticação do funcionário
     * 
     * @param Request $request
     * @return object
     */
    public function authentication(Request $request): object
    {
        // TO-DO: Realizar as devidas validações

        $payload = $request->only('email', 'password');

        if (!auth()->guard('employee_api')->attempt($payload)) {
            return response()->json([
                'data' => [],
                'message' => 'credênciais inválidas!'
            ], 401);
        }

        auth()->user()->tokens()->delete();
        $employee = auth()->user();
        $employeeAbilities = EmployeeAbility::where('employee_id', $employee->id)->get();
        $token = $employee->createToken('admin_token', $this->formatAbilities($employeeAbilities))->plainTextToken;

        return response()->json([
            'data' => $employee,
            'token' => $token,
            'message' => 'usuário logado!'
        ], 200);
    }

    /**
     * Realiza o logout do funcionário
     * 
     * @return object
     */
    public function logout(): object
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'data' => [],
            'message' => 'usuário deslogado',
        ], 200);
    }

}
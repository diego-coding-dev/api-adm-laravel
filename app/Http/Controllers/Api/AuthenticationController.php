<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\TypeUserAbility;
use App\Models\PendentPassword;
use App\Traits\HasResponseApi;
use App\Traits\SetAbilities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TheSeer\Tokenizer\Exception;
use function auth;

class AuthenticationController extends Controller
{

    use SetAbilities,
        HasResponseApi;

    /**
     * Verifica se a conta do email fornecido necessita configurar senha
     * 
     * @param Request $request
     * @return type
     */
    public function checkEmail(Request $request)
    {
        // TO-DO: realizar as devidas validações

        $formData = $request->only('email');

        if ($email = PendentPassword::where('email', $formData['email'])->first()) {
            $response['data'] = [
                'email' => $email
            ];

            return $this->makeResponse($response, 200, 'need_set_password');
        }

        $response['data'] = [
            'email' => $formData['email']
        ];

        return $this->makeResponse($response, 200, 'sucess');
    }

    /**
     * Configura a senha da conta e realiza o login
     * 
     * @param Request $request
     * @return type
     */
    public function setPassword(Request $request)
    {
        // TO-DO: realizar as devidas validações

        $formData = $request->all();

        try {
            DB::beginTransaction();

            PendentPassword::where('email', $formData['email'])->delete();
            Employee::where('email', $formData['email'])->update([
                'password' => bcrypt($formData['password']),
                'is_active' => true
            ]);

            DB::commit();
        } catch (Exception $exc) {
            DB::rollBack();

            $response['data'] = $formData;

            return $this->makeResponse($response, 500, 'error');
        }

        unset($formData['password_confirm']);

        return $this->authentication($formData);
    }

    /**
     * Inicia o processo de authenticação
     * 
     * @param Request $request
     * @return type
     */
    public function makeAuthentication(Request $request)
    {
        // TO-DO: Realizar as devidas validações

        $formData = $request->only('email', 'password');

        return $this->authentication($formData);
    }

    /**
     * Realiza o logout do funcionário
     * 
     * @return object
     */
    public function logout(): object
    {
        auth()->user()->tokens()->delete();

        $response['data'] = [];

        return $this->makeResponse($response, 200, 'logout');
    }

    /**
     * Realiza a autenticação do funcionário
     * 
     * @param array $data
     * @return object
     */
    private function authentication(array $data): object
    {

//        return response()->json($data);
        $data['is_active'] = true;

        if (!auth()->guard('employee_api')->attempt($data)) {
            $response['data'] = [];

            return $this->makeResponse($response, 404, 'not_found');
        }

        auth()->user()->tokens()->delete();
        $employee = auth()->user();
        $employeeAbilities = TypeUserAbility::where('type_user_id', $employee->type_user_id)->get();

        $token = $employee->createToken('admin_token', $this->formatAbilities($employeeAbilities))->plainTextToken;

        $response['data'] = [
            'data' => $employee,
            'token' => $token,
        ];

        return $this->makeResponse($response, 200, 'logged');
    }

}

<?php

namespace App\Http\Controllers\Api\Rh;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\PendentPassword;
use App\Traits\HasResponseApi;
use Illuminate\Http\Request;
use TheSeer\Tokenizer\Exception;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{

    use HasResponseApi;

    /**
     * Lista todos os funcionários cadastrados
     * 
     * @return object
     */
    public function list(): object
    {
        $response['data'] = [
            'employee_list' => Employee::paginate(10)
        ];

        return $this->makeResponse($response, 200, 'found');
    }

    /**
     * Cria a conta de um novo funcionário
     * 
     * @param Request $request
     * @return object
     */
    public function create(Request $request): object
    {
        // TO-DO: realizar as devidas validações

        $formData = $request->all();

        DB::beginTransaction();

        try {
            $employee = Employee::create($formData);
            PendentPassword::create(['email' => $employee->email]);

            DB::commit();
        } catch (Exception $exc) {
            DB::rollBack();

            $response['data'] = [];

            return $this->makeResponse($response, 500, 'error');
        }

        $response['data'] = [
            'employee' => $employee
        ];

        return $this->makeResponse($response, 201, 'created');
    }

    /**
     * Desativa a conta de um funcionário
     * 
     * @param string $employeeId
     * @return object
     */
    public function deactive(string $employeeId): object
    {
        if (!$employee = Employee::find($employeeId)) {
            $response['data'] = [];

            return $this->makeResponse($response, 404, 'not_found');
        }

        $employee->is_active = false;
        $employee->save();

        $response['data'] = [
            'employee' => $employee
        ];

        return $this->makeResponse($response, 200, 'deactivated');
    }
    
    /**
     * Ativa a conta de um funcionário
     * 
     * @param string $employeeId
     * @return object
     */
    public function active(string $employeeId): object
    {
        if (!$employee = Employee::find($employeeId)) {
            $response['data'] = [];

            return $this->makeResponse($response, 404, 'not_found');
        }

        $employee->is_active = true;
        $employee->save();

        $response['data'] = [
            'employee' => $employee
        ];

        return $this->makeResponse($response, 200, 'activated');
    }

}

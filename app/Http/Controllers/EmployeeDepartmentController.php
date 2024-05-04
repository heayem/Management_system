<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\EmployeeDepartment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class EmployeeDepartmentController extends Controller
{
    public function index()
    {
        $this->authorizeAdministrator();
        
        $departments = EmployeeDepartment::getusersAndDepartment()->orderBy('users.id')->get();
        return Inertia::render('AdminPage/ListEmployeeDepartment', ['users' => $departments]);
    }
    public function store(Request $request)
    {
        $this->authorizeAdministrator();

        $requestData = $request->all();
        $existingRecord = EmployeeDepartment::where([
            'user_Id' => $requestData['user_Id']['value'],
            'department_Id' => $requestData['department_Id']['value']
        ])->first();

        if ($existingRecord) {
            return redirect('employee-edit-department');
        } else {
            EmployeeDepartment::assignPermission($requestData);
        }
        return redirect()->route('employee-department');
    }

    public function department()
    {
        $this->authorizeAdministrator();

        $users = User::getNameAndId()->get();
        $departments = Department::getNameAndId()->get();
        return Inertia::render('AdminPage/AddUserToDepartment', ['users' => $users, 'departments' => $departments]);
    }
    public function destroy($user_Id, $departments_Id)
    {
        $this->authorizeAdministrator();

        if (EmployeeDepartment::removeFromDepartment($user_Id, $departments_Id)) {
            $departments = EmployeeDepartment::getusersAndDepartment()->orderBy('users.id')->get();
            return Inertia::render('AdminPage/ListEmployeeDepartment', ['users' => $departments]);
        }
        return redirect()->route('employee-department');
    }

    private function authorizeAdministrator()
    {
        if (!Auth::user()->hasRole('administrator')) {
            abort(403, 'Unauthorized action. Only administrators can access this resource.');
        }
    }
}

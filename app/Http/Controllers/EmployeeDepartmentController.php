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

        $departments = EmployeeDepartment::getUsersAndDepartment()->orderBy('users.id')->get();
        return Inertia::render('AdminPage/ListEmployeeDepartment', ['users' => $departments]);
    }
    public function indexBydepartment()
    {
        $this->authorizeAdministrator();

        $departments = EmployeeDepartment::getUsersByDepartment()->orderBy('users.id')->get();
        return Inertia::render('AdminPage/ListEmployeeInDepartment', ['users' => $departments]);
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
            return redirect()->route('employee-edit-department')->with('error', 'Failed to assign the user.');
        } else {
            EmployeeDepartment::assignPermission($requestData);
        }
        return redirect()->route('employee-department')->with('success', 'The assign was successfully.');
    }

    public function department()
    {
        $this->authorizeAdministrator();

        $users = User::getNameAndId()->get();
        $departments = Department::getNameAndId()->get();
        return Inertia::render('AdminPage/AddUserToDepartment', ['users' => $users, 'departments' => $departments]);
    }

    public function permission()
    {
        $this->authorizeAdministrator();

        $users = User::getNameAndIdBydepartment()->get();
        $departments = Department::getNameAndId()->get();
        return Inertia::render('AdminPage/AddWorkFlow', ['users' => $users, 'departments' => $departments]);
    }
    public function assignPermission(Request $request)
    {
        $this->authorizeAdministrator();

        $requestData = $request->all();
        $existingRecord = EmployeeDepartment::where([
            'user_Id' => $requestData['user_Id']['value'],
            'department_Id' => $requestData['department_Id']['value']
        ])->first();

        if ($existingRecord) {
            EmployeeDepartment::updatePermission($requestData);
        } else {
            return redirect()->route('employee-edit-department')->with('error', 'Failed to assign the user.');
        }
        return redirect()->route('employee-by-department')->with('success', 'The assign was successfully.');
    }

    public function destroy($user_Id, $departments_Id)
    {
        $this->authorizeAdministrator();

        $deleted = EmployeeDepartment::removeFromDepartment($user_Id, $departments_Id);
        if ($deleted)
            return redirect()->route('employee-department')->with('success', 'The user was remove successfully.');
        else
            return redirect()->route('employee-department')->with('error', 'Failed to remove the user.');

    }

    private function authorizeAdministrator()
    {
        if (!Auth::user()->hasRole(['administrator', 'department_administrator'])) {
            abort(403, 'Unauthorized action. Only administrators can access this resource.');
        }
    }
}

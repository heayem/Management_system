<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DepartmentController extends Controller
{

    public function index()
    {
        $departments = Department::all();
        return Inertia::render('AdminPage/ListDepartment', ['departments' => $departments]);
    }

    public function create()
    {
        $this->authorizeAdministrator();

        return Inertia::render('AdminPage/CreateDepartment');
    }

    public function edit($id)
    {
        $this->authorizeAdministrator();
        
        $department = Department::find($id)->first();
        return Inertia::render('AdminPage/UpdateDepartment', ['department' => $department]);
    }

    public function store(Request $request)
    {
        $this->authorizeAdministrator();

        $request->validate([
            'name' => 'required|string|max:255|unique:departments',
        ]);
        Department::create([
            'name' => $request->name
        ]);
        return redirect()->route('department');
    }



    public function update(Request $request)
    {
        $this->authorizeAdministrator();

        $request->validate([
            'name' => 'required|string|max:255|unique:departments',
        ]);
        $department = Department::find($request->id);
        $department->update([
            'name' => $request->name
        ]);

        return redirect()->route('department');
    }

    public function destroy($id)
    {
        $this->authorizeAdministrator();

        $department = Department::find($id);
        $department->delete();
        return redirect()->route('department');
    }

    private function authorizeAdministrator()
    {
        if (!Auth::user()->hasRole('administrator')) {
            abort(403, 'Unauthorized action. Only administrators can access this resource.');
        }
    }
}

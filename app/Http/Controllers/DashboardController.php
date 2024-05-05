<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


class DashboardController extends Controller
{
    public function index()
    {
        $this->authorizeAdministrator();

        $totalUsers = User::count();
        $totalDepartments = Department::count();

        return Inertia::render('AdminPage/Dashboard', ['totalUsers' => $totalUsers, 'totalDepartments' => $totalDepartments]);
    }


    private function authorizeAdministrator()
    {
        if (!Auth::user()->hasRole(['administrator'])) {
            abort(403, 'Unauthorized action. Only administrators can access this resource.');
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $this->authorizeAdministrator();

        $users = User::getusers()->get();
        return Inertia::render('AdminPage/ListEmployee', ['users' => $users]);

    }
    public function edit($id)
    {
        $this->authorizeAdministrator();

        $user = User::query()->where('id', $id)->get();
        return Inertia::render('AdminPage/UpdateUser', ['users' => $user]);
    }
    public function update(Request $request)
    {
        $this->authorizeAdministrator();

        $updated = User::updateUser($request->all());
        
        if ($updated) {
            return redirect('employee')->with('success', 'The user was updated successfully.');
        } else {
            return redirect('employee')->with('error', 'Failed to update the user.');
        }
    }
    public function destroy($id)
    {
        $this->authorizeAdministrator();

        $deleted = User::deleteUser($id);

        if ($deleted) {
            return redirect('employee')->with('success', 'The user was deleted successfully.');
        } else {
            return redirect('employee')->with('error', 'Failed to delete the user.');
        }
    }

    private function authorizeAdministrator()
    {
        if (!Auth::user()->hasRole(['administrator'])) {
            abort(403, 'Unauthorized action. Only administrators can access this resource.');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\RequestForm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RequestFormController extends Controller
{
    public function index()
    {
        $requestForm = RequestForm::getAllRequest()->get();
        return Inertia::render('ManagerPage/Home', ['requestForms' => $requestForm]);
    }

    public function updateStatus(Request $request)
    {

        $permission = User::myPermission()->first();
        $type = $request->type;
        $status = $request->status;
        $id = $request->id;

        if ($permission->approver === 1) {
            if ($type === 'mission') {
                $requestForm = RequestForm::find($id);
                $requestForm->update([
                    'status' => $status
                ]);
            }
        } elseif ($permission->approver === 2) {
            if ($type === 'leave') {
                $requestForm = RequestForm::find($id);
                $requestForm->update([
                    'status' => $status
                ]);
            }
        } elseif ($permission->approver === 3) {
            if ($type === 'leave' || $type === 'mission') {
                $requestForm = RequestForm::find($id);
                $requestForm->update([
                    'status' => $status
                ]);
            }
        }

        return redirect()->back();
    }

    public function show()
    {
        $user = Auth::user();
        $id = $user->id;
        $requestForm = RequestForm::join('departments','departments.id','=','request_forms.department_Id')
        ->select('request_forms.id as id','request_forms.title as title',
        'request_forms.start_date as start_date',
        'request_forms.end_date as end_date',
        'request_forms.reason as reason',
        'request_forms.status as status',
        'request_forms.type as type',
        'departments.name as department_name')
        ->where('request_forms.user_Id', $id)
        ->orderByDesc('request_forms.id')->get();
        return Inertia::render('UserPage/Home', ['requestForms' => $requestForm]);
    }
    public function createForStaff()
    {
        $user = Auth::user();
        $id = $user->id;
        $departments = Department::getDepartment()->where('employee_departments.user_Id', $id)->get();
        return Inertia::render('UserPage/CreateRequestForm', ['departments' => $departments]);
    }

    public function createForManger()
    {
        $user = Auth::user();
        $id = $user->id;
        $departments = Department::getDepartment()->where('employee_departments.user_Id', $id)->get();
        return Inertia::render('ManagerPage/CreateRequestForm', ['departments' => $departments]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'department_Id' => 'required|exists:departments,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ], [
            'end_date.after_or_equal' => 'The end date must be after or equal to the start date.'
        ]);

        $startDate = date('Y/m/d', strtotime($request->start_date));
        $endDate = date('Y/m/d', strtotime($request->end_date));

        RequestForm::create([
            'title' => $request->title,
            'department_Id' => $request->department_Id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'reason' => $request->reason,
            'type' => $request->type,
            'user_Id' => Auth::user()->id
        ]);
        return redirect()->route('request');
    }


}

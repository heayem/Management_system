<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class RequestForm extends Model
{
    protected $fillable = [
        'title',
        'department_Id',
        'user_Id',
        'start_date',
        'end_date',
        'reason',
        'type',
        'status'
    ];
    use HasFactory;

    public function scopeGetAllRequest($query)
    {
        $permission = '';
        if (User::myPermission()->first()->approver == 1) {
            $permission = 'mission';
        } elseif (User::myPermission()->first()->approver == 2) {
            $permission = 'leave';
        } elseif (User::myPermission()->first()->approver == 3) {
            $permission = ['mission', 'leave'];
        }
        return $query
            ->join('users', 'users.id', '=', 'request_forms.user_Id')
            ->join('employee_departments', 'employee_departments.user_Id', '=', 'users.id')
            ->leftjoin('departments', 'departments.id', '=', 'employee_departments.department_Id')
            ->select(
                'request_forms.id as id',
                DB::raw("CONCAT(users.fname, ' ', users.lname) AS user_name"),
                'users.role as role',
                'departments.name as department_name',
                'request_forms.title as title',
                'request_forms.start_date as start',
                'request_forms.end_date as end',
                'request_forms.reason as reason',
                'request_forms.type as type',
                'request_forms.status as status',
            )
            ->where('employee_departments.department_Id', User::getDeparment())
            ->where('request_forms.type', $permission)
            ->distinct();
    }


    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_Id');
    }
}

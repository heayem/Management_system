<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployeeDepartment extends Model
{
    protected $fillable = ['user_Id', 'department_Id', 'approver'];
    use HasFactory;

    public function scopeGetUsersAndDepartment($query)
    {
        return $query->leftjoin('users', 'users.id', '=', 'employee_departments.user_Id')
            ->leftjoin('departments', 'departments.id', '=', 'employee_departments.department_Id')
            ->select('users.id', 'users.fname', 'users.lname', 'users.profile', 'users.phone', 'users.email', 'users.gender', 'users.role', 'departments.name as department', 'departments.id as departmentID', 'employee_departments.approver as approver')
            ->where('users.role', '!=', 'administrator');
    }

    public function scopeGetUsersByDepartment($query)
    {
        return $query->leftjoin('users', 'users.id', '=', 'employee_departments.user_Id')
            ->leftjoin('departments', 'departments.id', '=', 'employee_departments.department_Id')
            ->select('users.id', 'users.fname', 'users.lname', 'users.profile', 'users.phone', 'users.email', 'users.gender', 'users.role', 'departments.name as department', 'departments.id as departmentID', 'employee_departments.approver as approver')
            ->whereIn('employee_departments.department_Id',User::getDeparment()->get()->pluck('department_Id'))
            ->where('users.role', '!=', 'department_administrator')
            ->where('users.role', '!=', 'administrator');
    }

    public static function assignPermission($data)
    {
        return self::create([
            'approver' => $data['approver']['value'] ?? 0,
            'user_Id' => $data['user_Id']['value'],
            'department_Id' => $data['department_Id']['value']
        ]);
    }
    public static function updatePermission($data)
    {
        $userId = $data['user_Id']['value'] ?? null;
        $departmentId = $data['department_Id']['value'] ?? null;
        $approver = $data['approver']['value'] ?? null;

        if ($userId !== null && $departmentId !== null && $approver !== null) {
            $affectedRows = self::where('user_Id', $userId)->where('department_Id', $departmentId)
                ->update(['approver' => $approver]);
            return $affectedRows > 0;
        } else {
            return false;
        }
    }

    public static function removeFromDepartment($user_Id, $departments_Id)
    {
        $record = self::where('user_Id', $user_Id)->where('department_Id', $departments_Id)->first();
        if ($record) {
            return self::where('user_Id', $user_Id)
                ->where('department_Id', $departments_Id)
                ->delete();
        }
        return false;
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_Id');
    }
    public function departments(): HasMany
    {
        return $this->hasMany(Department::class, 'department_Id');
    }
}

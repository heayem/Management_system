<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployeeDepartment extends Model
{
    protected $fillable = ['user_Id', 'department_Id','approver'];
    use HasFactory;

    public function scopeGetusersAndDepartment($query)
    {
        return $query->leftjoin('users', 'users.id', '=', 'employee_departments.user_Id')
            ->leftjoin('departments', 'departments.id', '=', 'employee_departments.department_Id')
            ->select('users.id', 'users.fname', 'users.lname', 'users.profile', 'users.phone', 'users.email', 'users.gender', 'users.role', 'departments.name as department', 'departments.id as departmentID')
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = ['name'];
    use HasFactory;

    public function scopeGetNameAndId($query)
    {
        return $query->select('name as name', 'id AS value');
    }
    public function scopeGetDepartment($query)
    {
        return $query
            ->join('employee_departments', 'departments.id', '=', 'employee_departments.department_Id')
            ->select('departments.name as name','departments.id as value')
            ->distinct(); 
    }

    public function employeeDepartments(): HasMany
    {
        return $this->hasMany(EmployeeDepartment::class, 'department_Id');
    }
}

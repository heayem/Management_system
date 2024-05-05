<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fname',
        'lname',
        'email',
        'phone',
        'dob',
        'place_of_birth',
        'gender',
        'profile',
        'role',
        'current_address',
        'password',
    ];

    public function scopeMyPermission($query)
    {
        return $query->leftjoin('employee_departments', 'users.id', '=', 'employee_departments.user_Id')
            ->select('employee_departments.approver')
            ->where('users.id', Auth::user()->id);
    }


    public function hasRole($roles)
    {
        return in_array($this->role, $roles);
    }

    public static function deleteUser($id)
    {
        $user = self::find($id);
        $oldProfilePath = public_path('images/' . $user->profile);
        if (file_exists($oldProfilePath)) {
            unlink($oldProfilePath);
        }
        $user->delete();
        return redirect('employee');
    }

    public static function updateUser($data)
    {
        $user = self::find($data['id']);
        $profile = $user->profile;
        if (!$user) {
            return null;
        }
        if (isset($data['profile']) && is_file($data['profile'])) {
            $oldProfilePath = public_path('images/' . $user->profile);
            if (file_exists($oldProfilePath)) {
                unlink($oldProfilePath);
            }
            $profile = self::uploadImage($data['profile']);
        } else {
            $profile = $user->profile;
        }

        $carbonDate = Carbon::parse($data['dob']);
        $formattedDate = $carbonDate->format('Y/m/d');

        $user->update([
            'fname' => $data['fname'] ?? $user->fname,
            'lname' => $data['lname'] ?? $user->lname,
            'email' => isset($data['email']) ? ($data['email'] ?: $user->email) : $user->email,
            'phone' => $data['phone'] ?? $user->phone,
            'dob' => $formattedDate,
            'place_of_birth' => $data['place_of_birth'] ?? $user->place_of_birth,
            'gender' => $data['gender'] ?? $user->gender,
            'role' => $data['role']['value'] ?? $user->role,
            'profile' => $profile,
            'current_address' => $data['current_address'] ?? $user->current_address,
        ]);

        if (isset($data['password'])) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        return $user;
    }

    public function scopeGetNameAndId($query)
    {
        return $query->select(DB::raw("CONCAT(fname, ' ', lname) AS name"), 'id AS value', 'profile as img', 'role as role')->where('role', '!=', 'administrator');
    }

    public function scopeGetNameAndIdBydepartment($query)
    {
        return $query->leftJoin('employee_departments', 'users.id', '=', 'employee_departments.user_Id')
            ->join('departments', 'departments.id', '=', 'employee_departments.department_Id')
            ->select(DB::raw("CONCAT(users.fname, ' ', users.lname) AS name"), 'users.id AS value', 'departments.name AS department_name', 'departments.id AS department_Id', 'users.role AS role')
            ->whereIn('employee_departments.department_Id', $this->getDeparment()->pluck('department_Id')->toArray())
            ->where('role', '!=', 'administrator')
            ->where('role', '!=', 'department_administrator')
            ->orderBy('employee_departments.department_Id');
    }


    public function scopeGetusers($query)
    {
        return $query->leftjoin('employee_departments', 'users.id', '=', 'employee_departments.user_Id')
            ->select('users.id', 'users.fname', 'users.lname', 'users.profile', 'users.phone', 'users.email', 'users.gender', 'users.role')
            ->distinct('users.id');
    }

    public function scopeGetDeparment($query)
    {
        return $query->leftjoin('employee_departments', 'users.id', '=', 'employee_departments.user_Id')
            ->select('employee_departments.department_Id')
            ->where('users.id', Auth::user()->id);
    }



    public static function createUser($data)
    {
        $carbonDate = Carbon::parse($data['dob']);
        $formattedDate = $carbonDate->format('Y/m/d');
        $profile = self::uploadImage($data['profile']);
        return self::create([
            'fname' => $data['fname'],
            'lname' => $data['lname'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'dob' => $formattedDate,
            'place_of_birth' => $data['place_of_birth'],
            'gender' => $data['gender'],
            'role' => $data['role']['value'],
            'profile' => $profile,
            'current_address' => $data['current_address'],
            'password' => Hash::make($data['password']),
        ]);
    }

    private static function uploadImage($file)
    {
        $filename = uniqid() . '-' . now()->timestamp . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/'), $filename);
        return $filename;
    }



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function employeeDepartments()
    {
        return $this->hasMany(EmployeeDepartment::class, 'user_Id');
    }
    public function requestForm()
    {
        return $this->hasMany(RequestForm::class, 'user_Id');
    }
}

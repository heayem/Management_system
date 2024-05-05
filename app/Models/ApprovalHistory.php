<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class ApprovalHistory extends Model
{
    protected $fillable = ['approver_Id','request_Id','status'];
    use HasFactory;

    public static function rememberApprover($request_Id,$status){
        return self::create([
            'approver_Id'=> Auth::user()->id,
            'request_Id'=> $request_Id,
            'status'=> $status
        ]);
    }
    
    public function requestForm(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_Id');
    }

}

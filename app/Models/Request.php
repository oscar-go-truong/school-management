<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Request extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_request_id',
        'user_approve_id',
        'type',
        'status',
        'content'
    ];


    public function userRequest(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_request_id");
    }

    public function userApprove(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_approve_id');
    }


    public function scopeStatus($query, $input){
        if($input['status'])
            return $query->where('status', $input['status']);
        return $query;
    }

    public function scopeType($query, $input){
        if($input['type'])
            return $query->where('type', $input['type']);
        return $query;
    }
}

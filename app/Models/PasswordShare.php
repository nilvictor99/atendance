<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordShare extends Model
{
    protected $fillable = [
        'password_id',
        'shared_by',
        'shared_with',
        'permissions',
    ];

    public function password()
    {
        return $this->belongsTo(PasswordVault::class);
    }

    public function sharedBy()
    {
        return $this->belongsTo(User::class, 'shared_by');
    }

    public function sharedWith()
    {
        return $this->belongsTo(User::class, 'shared_with');
    }
}

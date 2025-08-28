<?php

namespace App\Models;

use App\Observers\PasswordVaultObserver;
use App\Traits\Auth\BelongsToAuthenticatedUser;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([PasswordVaultObserver::class])]
class PasswordVault extends Model
{
    use BelongsToAuthenticatedUser;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'username',
        'password',
        'url',
        'notes',
        'type',
        'active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function passwordShares()
    {
        return $this->hasMany(PasswordShare::class, 'password_id');
    }
}

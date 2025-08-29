<?php

namespace App\Models;

use App\Observers\PasswordVaultObserver;
use App\Traits\Auth\BelongsToAuthenticatedUser;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
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

    public function scopeVisibleToUser(Builder $query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('type', 'public')
                ->orWhere(function ($sub) use ($userId) {
                    $sub->where('type', 'private')
                        ->where('user_id', $userId);
                })
                ->orWhereHas('passwordShares', function ($sub) use ($userId) {
                    $sub->where('shared_with', $userId);
                });
        });
    }

    public function canView($userId)
    {
        if ($this->user_id == $userId) {
            return true;
        }
        if ($this->type == 'public') {
            return true;
        }

        return $this->passwordShares()->where('shared_with', $userId)->whereIn('permissions', ['view', 'edit'])->exists();
    }

    public function canEdit($userId)
    {
        if ($this->user_id == $userId) {
            return true;
        }

        return $this->passwordShares()->where('shared_with', $userId)->where('permissions', 'edit')->exists();
    }
}

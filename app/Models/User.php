<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'permissions',
        'assigned_shed_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'permissions' => 'array',
        ];
    }

    /**
     * التحقق مما إذا كان المستخدم لديه صلاحية معينة
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->role === 'admin') {
            return true;
        }

        return is_array($this->permissions) && in_array($permission, $this->permissions);
    }

    /**
     * التحقق مما إذا كان المستخدم أدمن
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function assignedShed()
    {
        return $this->belongsTo(\App\Models\Shed::class, 'assigned_shed_id');
    }

    public function isShedScoped(): bool
    {
        if ($this->role === 'admin') {
            return false;
        }

        if (!in_array('sheds', $this->permissions ?? [])) {
            return false;
        }

        return !is_null($this->assigned_shed_id);
    }

    public function scopeForShed($query)
    {
        if ($this->isShedScoped()) {
            return $query->where('shed_id', $this->assigned_shed_id);
        }

        return $query;
    }
}

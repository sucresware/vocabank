<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'fourSucres_account',
    ];

    protected $hidden = [
        'password', 'remember_token', 'email', 'fourSucres_account',
    ];

    protected $casts = [
        'fourSucres_account' => 'array',
    ];

    public function samples()
    {
        return $this->hasMany(Sample::class);
    }
}

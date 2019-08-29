<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles, LogsActivity;

    protected $fillable = [
        'name', 'email', 'password', 'fourSucres_account',
    ];

    protected $hidden = [
        'password', 'remember_token', 'email', 'fourSucres_account', 'settings',
    ];

    protected $casts = [
        'fourSucres_account' => 'array',
        'settings'           => 'array',
    ];

    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;

    public function samples()
    {
        return $this->hasMany(Sample::class);
    }

    public function getSetting($key, $default = null)
    {
        return data_get($this->settings, $key, $default);
    }

    public function setSetting($key, $value)
    {
        $currentSettings = $this->settings;
        data_set($currentSettings, $key, $value);
        $this->settings = $currentSettings;
        $this->save();

        return $this;
    }

    public function setMultipleSettings(array $settings)
    {
        $currentSettings = $this->settings;
        foreach ($settings as $key => $value) {
            data_set($currentSettings, $key, $value);
        }

        $this->settings = $currentSettings;
        $this->save();

        return $this;
    }
}

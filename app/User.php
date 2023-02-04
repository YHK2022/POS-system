<?php

namespace App;

use App\Company;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    protected $fillable = [
        'name', 'email', 'password', "phone", "company_name", "company_id", "role_id", "biller_id", "warehouse_id", "is_active", "is_deleted",
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isActive()
    {
        return $this->is_active;
    }

    public function holiday()
    {
        return $this->hasMany('App\Holiday');
    }

    public function companies()
    {
        return $this->belongsTo('App\Company');
    }
}
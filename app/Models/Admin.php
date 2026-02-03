<?php

namespace App\Models;

use App\Notifications\AdminResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable, HasFactory, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard = 'admin';
    protected $dates = ['date_of_birth'];
    protected $fillable = [
    'role_id',
    'name',
    'username',
    'email',
    'password',
    'plain_password',
    'media_id',
    'google2fa_secret',
    'google2fa_enabled',
    'ip_enabled',
    'status_id',
    'login_time_restriction_enabled',
    'login_allowed_from',
    'login_allowed_to'
];

    public function godowns(){
        return $this->belongsToMany(Godown::class, 'godown_admins');
    }

    public function media(){
        return $this->hasOne(Media::class,'id','media_id');
    }

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role(){
        return $this->hasOne(Role::class,'id','role_id');
    }
    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class, 'role_users');
    // }
    //  public function hasAccess(string $permissions) :bool
    // {
    //     if($this->role->hasAccess($permissions)) {
    //         return true;
    //     }
    //     return false;
    // }

     public function hasAccess($permissions) :bool
    {
        $permissions = gettype($permissions) == 'array' ? $permissions : [$permissions];
        if($this->role->hasAccess($permissions)) {
            return true;
        }
        return false;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPasswordNotification($token));
    }

    public function ips(){
        return $this->hasMany(AdminIp::class);
    }

    protected function google2faSecret(): Attribute{

        return new Attribute(

            get: fn ($value) => decrypt($value),

            set: fn ($value) => encrypt($value),

        );

    }

}

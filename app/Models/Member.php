<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Member extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;

    //protected $connection = 'test_local';

    protected $table = 'members';

    protected $guard_name = 'member';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function company()
    {
        return $this->hasOne(Company::class, 'member_id', 'id');
    }
}

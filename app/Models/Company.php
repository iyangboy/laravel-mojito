<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $connection = 'test_local';

    protected $table = 'company';

    protected $fillable = [
        'permission_name', 'sub_account_num', 'mobile', 'count', 'status', 'type', 'trial_at',
        'company_name', 'email', 'formal', 'password'
    ];

    public function accountSubset()
    {
        return $this->hasMany(AccountSubset::class, 'company_id', 'id');
    }
}

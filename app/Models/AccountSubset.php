<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountSubset extends Model
{
    protected $connection = 'test_local';

    protected $table = 'account_subset';

    protected $fillable = [
        'company_id', 'name', 'email', 'mobile', 'password'
    ];


    public function company()
    {
        return $this->belongsTo(Account::class, 'company_id', 'id');
    }
}

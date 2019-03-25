<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountSubset;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request, Account $account)
    {
        $accounts = $account::query()->where(request_intersect(['permission_name','email']))->paginate(20);
        return $accounts;
    }

    // 创建新企业
    public function store(Request $request)
    {
        $company = $request->company;
        $email = $request->email;
        $formal = $request->formal;
        $name = $request->name;
        $phone = $request->phone;
        $trial_at = $request->trial_at;
        $accounts = $request->accounts;

        //return count($accounts);
        $sub_account_num = count($accounts);
        $account = Account::create([
            'permission_name' => $company,
            'sub_account_num' => $sub_account_num,
            'company' => $company,
            'email' => $email,
            'formal' => $formal,
            'mobile' => $phone,
            'trial_at' => $trial_at,
            'account_subset' => $accounts
        ]);
        // return $account;
        foreach ($accounts as $ls) {
            AccountSubset::create([
                'company_id' => $account->id,
                'name' => $ls['name'],
                'email' => $ls['name'],
                'password' => $ls['password'],
            ]);
        }
        return 1;
        //$account->accountSubset->create($accounts);

    }

    // 编辑
    public function update(Request $request, $id)
    {
        $account = Account::find($id);
        $account->update($request->all());
        return 1;
    }

}

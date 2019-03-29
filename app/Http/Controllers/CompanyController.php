<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountSubset;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request, Company $company)
    {
        $company_data = $company::query()->where(request_intersect(['permission_name', 'email']))->paginate(20);
        return $company_data;
    }

    // 创建新企业
    public function store(Request $request)
    {
        $company_name = $request->company_name;
        $email = $request->email;
        $password = $request->password;
        $formal = $request->formal;
        $phone = $request->phone;
        $trial_at = $request->trial_at;
        $accounts = $request->accounts;

        //return count($accounts);
        $sub_account_num = count($accounts);
        $company = Company::create([
            'permission_name' => $company_name,
            'sub_account_num' => $sub_account_num,
            'company_name' => $company_name,
            'email' => $email,
            'password' => bcrypt($password),
            'formal' => $formal,
            'mobile' => $phone,
            'trial_at' => $trial_at,
            //'account_subset' => $accounts
        ]);
        // return $account;
        foreach ($accounts as $ls) {
            AccountSubset::create([
                'company_id' => $company->id,
                'name' => $ls['name'],
                'email' => $ls['name'],
                'password' => bcrypt($ls['password']),
            ]);
        }
        return $company->id;
        //$account->accountSubset->create($accounts);

    }

    // 编辑
    public function update(Request $request, $id)
    {
        $company = Company::find($id);
        $data = $request->all();
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        $company->update($data);
        return $company->id;
    }

}

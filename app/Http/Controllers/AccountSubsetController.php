<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountSubset;
use App\Models\Company;
use Illuminate\Http\Request;
use Hash;

class AccountSubsetController extends Controller
{
    public function index(Request $request)
    {
        $subset = AccountSubset::query();
        $accounts = $subset->with(['company_name'])->paginate(10);
        return $accounts;
    }

    public function companySubset(Request $request, $company_id)
    {
        $subset = AccountSubset::query()->with(['company'])->where('company_id', $company_id)->paginate(10);
        return $subset;
    }

    // 创建子账户
    public function store(Request $request)
    {
        $data = $request->all();
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        $account = AccountSubset::create($data);

        $company = $account->company;
        $company->sub_account_num = count($company->accountSubset);
        $company->save();

        return $account->id;
    }

    // 编辑
    public function update(Request $request, $id)
    {
        $account = AccountSubset::find($id);
        $data = $request->all();
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        $account->update($data);
        return $account->id;
    }

    // 删除
    public function destroy($id)
    {
        $account = AccountSubset::find($id);
        $account->delete();

        // 子账号计数
        $company = $account->company;;
        $company->sub_account_num = count($company->accountSubset);
        $company->save();

        return $account->id;
    }

    // 密码验证
    public function passwordValidate(Request $request)
    {
        $id = $request->id;
        $password = $request->password;
        $account = AccountSubset::find($id);
        if (Hash::check($password, $account->password)) {
            return 1;
        }
        return 0;
    }
}

<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountSubset;
use Illuminate\Http\Request;

class AccountSubsetController extends Controller
{
    public function index(Request $request)
    {
        $subset = AccountSubset::query();
        $accounts = $subset->with(['company'])->paginate(20);
        return $accounts;
    }

    public function companySubset(Request $request, $company_id)
    {
        $subset = AccountSubset::query()->with(['company'])->where('company_id', $company_id)->paginate(20);
        return $subset;
    }

    // 创建子账户
    public function store(Request $request)
    {
        $account = AccountSubset::create($request->all());
        return $account->id;
    }

    // 编辑
    public function update(Request $request, $id)
    {
        $account = AccountSubset::find($id);
        $account->update($request->all());
        return $account->id;
    }

    // 删除
    public function destroy($id)
    {
        $account = AccountSubset::find($id);

        $account->delete();

        return $account->id;
    }

    // 密码验证
    public function passwordValidate(Request $request)
    {
        $id = $request->id;
        $password = $request->password;
        $account = AccountSubset::find($id);
        if ($password == $account->password) {
            return 1;
        }
        return 0;
    }
}

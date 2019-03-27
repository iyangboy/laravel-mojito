<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\AccountSubset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyAccountController extends Controller
{

    public function __construct()
    {
        //$this->company = Auth::user()->company;
    }

    public function index(Request $request)
    {
        $company = Auth::user()->company;
        $accounts = AccountSubset::query()->with(['company'])->where('company_id', $company->id)->paginate(20);
        return $accounts;
    }

    // 创建子账户
    public function store(Request $request)
    {
        $data = $request->all();
        $data['company_id'] = Auth::user()->company->id ?: 0;
        $account = AccountSubset::create($data);
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
        return 0;
        $id = $request->id;
        $password = $request->password;
        $account = AccountSubset::find($id);
        if ($password == $account->password) {
            return 1;
        }
        return 0;
    }
}

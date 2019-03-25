<?php

namespace App\Http\Controllers;

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

    // 编辑
    public function update(Request $request, $id)
    {
        $account = Account::find($id);
        $account->update($request->all());
        return 1;
    }

}

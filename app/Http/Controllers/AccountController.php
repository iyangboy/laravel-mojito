<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request, Account $account)
    {
        $accounts = $account->paginate(20);
        return $accounts;
    }

    // 编辑
    public function update(Request $request, $id)
    {
        $account = Account::find($id);
        $account->update($request->all());
        return 1;
    }

}

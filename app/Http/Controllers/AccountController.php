<?php

namespace App\Http\Controllers;

use App\Constants\AccountConstants;
use App\Http\Requests\StoreAccountRequest;
use App\Models\Account;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accounts = Account::all();
        return response(["data" => $accounts]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountRequest $request)
    {
        $account = Account::create(
            [
                'user_id' => $request['user_id'],
                'balance' => AccountConstants::INITIAL_AMOUNT,
            ]
        );

        return response(["data" => $account]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        return response(["data" => $account]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        $account->delete();

        return response(["data" => "Success"]);
    }
}

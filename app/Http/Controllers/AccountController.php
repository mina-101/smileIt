<?php

namespace App\Http\Controllers;

use App\Constants\AccountConstants;
use App\Constants\TransactionConstants;
use App\Http\Requests\DepositAccountRequest;
use App\Http\Requests\StoreAccountRequest;
use App\Models\Account;
use App\Models\Transaction;
use http\Env\Request;
use Illuminate\Support\Facades\DB;

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

    /**
     * deposit money from one account to another
     * @param DepositAccountRequest $request
     * @return void
     */
    public function deposit(DepositAccountRequest $request)
    {
        $sourceAccount = Account::find($request->source_account);
        $destinationAccount = Account::find($request->destination_account);

        if ($sourceAccount->balance - $request->amount < AccountConstants::MIN_AMOUNT) {

            return response(["message" => "Account balance is low."], 422);
        }

        $transactionUuid = uuid_create();
        try {
            DB::transaction(function () use ($sourceAccount, $destinationAccount, $request, $transactionUuid) {
                Transaction::create([
                    'account_id' => $sourceAccount->id,
                    'amount' => -($request->amount),
                    'type' => TransactionConstants::TYPE_WITHDRAW,
                    'uuid' => $transactionUuid,
                ]);
                $sourceAccount->update([
                    'balance' => ($sourceAccount->balance - $request->amount)
                ]);

                Transaction::create([
                    'account_id' => $destinationAccount->id,
                    'amount' => $request->amount,
                    'type' => TransactionConstants::TYPE_DEPOSIT,
                    'uuid' => $transactionUuid,
                ]);
                $destinationAccount->update([
                    'balance' => ($destinationAccount->balance + $request->amount)
                ]);

            });

            return response(["message" => "Operation was done successfully."]);
        } catch (\Exception $exception) {
            throw new \Exception("ERROR! Transaction Failed");
        }
    }
}

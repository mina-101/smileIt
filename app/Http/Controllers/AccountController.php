<?php

namespace App\Http\Controllers;

use App\Constants\AccountConstants;
use App\Constants\TransactionConstants;
use App\Http\Requests\DepositAccountRequest;
use App\Http\Requests\StoreAccountRequest;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->isAdmin()) {
            $accounts = Account::all();
        } else {
            $accounts = Auth::user()->accounts;
        }

        return response(["data" => $accounts]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountRequest $request)
    {
        try {
            $account = DB::transaction(function () use ($request) {
                $account = Account::create(
                    [
                        'user_id' => $request['user_id'],
                        'balance' => AccountConstants::INITIAL_AMOUNT,
                    ]
                );
                Transaction::create([
                    'account_id' => $account->id,
                    'amount' => AccountConstants::INITIAL_AMOUNT,
                    'type' => TransactionConstants::TYPE_DEPOSIT,
                    'uuid' => uuid_create(),
                    'balance' => AccountConstants::INITIAL_AMOUNT,
                ]);
                return $account;
            });
            return response(["data" => $account]);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        if (Auth::user()->cannot('view', $account)) {
            abort(403, "Forbidden");
        }

        return response(["data" => $account]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        if (Auth::user()->cannot('delete', $account)) {
            abort(403, "Forbidden");
        }

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

        if (Auth::user()->cannot('deposit', $sourceAccount)) {
            abort(403, "Forbidden");
        }

        if ($sourceAccount->balance - $request->amount < AccountConstants::MIN_AMOUNT) {

            return response(["message" => "Account balance is low."], 422);
        }

        $transactionUuid = uuid_create();
        try {
            DB::transaction(function () use ($sourceAccount, $destinationAccount, $request, $transactionUuid) {
                $sourceAccountNewBalance = $sourceAccount->balance - $request->amount;
                $destinationAccountNewBalance = $destinationAccount->balance + $request->amount;
                Transaction::create([
                    'account_id' => $sourceAccount->id,
                    'amount' => -($request->amount),
                    'type' => TransactionConstants::TYPE_WITHDRAW,
                    'uuid' => $transactionUuid,
                    'balance' => $sourceAccountNewBalance,
                ]);
                $sourceAccount->update([
                    'balance' => $sourceAccountNewBalance
                ]);

                Transaction::create([
                    'account_id' => $destinationAccount->id,
                    'amount' => $request->amount,
                    'type' => TransactionConstants::TYPE_DEPOSIT,
                    'uuid' => $transactionUuid,
                    'balance' => $destinationAccountNewBalance,
                ]);
                $destinationAccount->update([
                    'balance' => $destinationAccountNewBalance
                ]);

            });

            return response(["message" => "Operation was done successfully."]);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * show transfer history af an account
     * @param Account $account
     * @return void
     */
    public function history(Account $account)
    {
        if (Auth::user()->cannot('history', $account)) {
            abort(403, "Forbidden");
        }

        $transactions = $account->transactions;

        return response(["data" => $transactions]);
    }

    /**
     * show balance af an account
     * @param Account $account
     * @return void
     */
    public function balance(Account $account)
    {
        if (Auth::user()->cannot('balance', $account)) {
            abort(403, "Forbidden");
        }

        return response(["data" => number_format($account->balance)]);
    }
}

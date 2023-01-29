<?php

namespace App\Http\Controllers\EntryPerson;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\BankController;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Bank;
use App\Models\Transaction;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class WithdrawalTransactionController extends Controller
{
    public function create(Request $request, $bank_id)
    {
        abort_if(Gate::denies('transaction_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = Auth::user();
        $roles = $user->roles()->pluck('title')->toArray();

        if(in_array('Entry Person', $roles)){
            $group = $user->group;
            $banks = $group->banks->pluck('id')->toArray();
            $transactions = Transaction::whereIn('bank_id', $banks)->where('transaction_type', 'Withdrawal')->latest()->take(5)->get();
            return view('entry_person.transactions.withdrawal', compact('transactions','bank_id'));
        }
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('transaction_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return DB::transaction(function () use ($request){

            $bank_id = $request->bank_id;
            $amount = $request->amount;

            $request->request->add(['transaction_type' => 'Withdrawal']);
            $request->request->add(['status' => 'Approved']);


            $bank = Bank::find($bank_id);
            $bank->balance = $bank->balance - $amount;
            $bank->save();

            $request->request->add(['entry_user_id' => Auth::id()]);
            $request->request->add(['entry_datetime' => now()]);

            $transaction = Transaction::create($request->all());

            return back();
        });
    }

    public function update(Request $request)
    {

        if ($request->ajax()) {

            $name = $request->name;
            $value = $request->value;

            $transaction = Transaction::find($request->pk);

            if($name=='amount'){
                // undo old bank balance change
                $bank = $transaction->bank;
                $bank->balance = ($bank->balance + $transaction->amount);

                // update bank balance
                $bank->balance = $bank->balance - $value;
                $bank->save();
            }

            $transaction->$name = $value;
            $transaction->save();

            return response()->json(['success' => true]);
        }
    }
}

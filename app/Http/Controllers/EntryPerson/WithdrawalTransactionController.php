<?php

namespace App\Http\Controllers\EntryPerson;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Transaction;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WithdrawalTransactionController extends Controller
{
    public function create(Request $request, $bank_id)
    {
        abort_if(Gate::denies('transaction_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = Auth::user();
        $bank = Bank::find($bank_id);

        $group = $user->group;
        $banks = $group->banks->pluck('id')->toArray();

        if(!in_array($request->bank_id, $banks)){
            return;
        }

        $transactions = Transaction::where('bank_id', $bank_id)->where('transaction_type', 'Withdrawal')->latest()->take(5)->get();
        return view('entry_person.transactions.withdrawal', compact('transactions','bank'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('transaction_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = Auth::user();
        $group = $user->group;
        $banks = $group->banks->pluck('id')->toArray();

        if(!in_array($request->bank_id, $banks)){
            return;
        }

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

            $request->request->add(['approver_id' => Auth::id()]);
            $request->request->add(['approve_datetime' => now()]);

            $transaction = Transaction::create($request->all());

            return back();
        });
    }

    public function update(Request $request)
    {
        abort_if(Gate::denies('transaction_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = Auth::user();
        $group = $user->group;
        $banks = $group->banks->pluck('id')->toArray();

        if ($request->ajax()) {

            $name = $request->name;
            $value = $request->value;

            $transaction = Transaction::find($request->pk);
            $bank = $transaction->bank;

            if(!in_array($bank->id, $banks)){
                return;
            }

            if($name=='amount'){
                // undo old bank balance change
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

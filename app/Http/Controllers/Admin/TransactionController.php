<?php

namespace App\Http\Controllers\Admin;

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

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('transaction_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {

            $user = Auth::user();
            $roles = $user->roles()->pluck('title')->toArray();

            if(in_array('Entry Person', $roles)){
                $group = $user->group;
                $banks = $group->banks->pluck('id')->toArray();
                $query = Transaction::whereIn('bank_id', $banks)->with(['bank', 'entry_user', 'approver'])->latest()->take(50)->select(sprintf('%s.*', (new Transaction())->table));
            } else if(in_array('Approver', $roles)){
                $country = $user->country;
                $banks = $user->country->countryBanks->pluck('id')->toArray();
                $query = Transaction::whereIn('bank_id', $banks)->with(['bank', 'entry_user', 'approver'])->latest()->take(50)->select(sprintf('%s.*', (new Transaction())->table));
            }else{
                $query = Transaction::with(['bank', 'entry_user', 'approver'])->latest()->select(sprintf('%s.*', (new Transaction())->table));
            }

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) use ($roles){
                $viewGate = 'transaction_show';
                $editGate = 'transaction_edit';
                $deleteGate = 'transaction_delete';
                $crudRoutePart = 'transactions';
                if(in_array('Admin', $roles)){
                    return view('partials.datatablesEditActions', compact(
                        'viewGate',
                        'editGate',
                        'crudRoutePart',
                        'row'
                    ));
                }else{
                    return view('partials.datatablesReadOnlyActions', compact(
                        'viewGate',
                        'crudRoutePart',
                        'row'
                    ));
                }
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('customer_name', function ($row) {
                return $row->customer_name ? $row->customer_name : '';
            });
            $table->editColumn('amount', function ($row) {
                return $row->amount ? $row->amount : '';
            });
            $table->editColumn('reference', function ($row) {
                return $row->reference ? $row->reference : '';
            });
            $table->editColumn('deposit_no', function ($row) {
                return $row->deposit_no ? $row->deposit_no : '';
            });
            $table->editColumn('status', function ($row) {
                return $row->status ? Transaction::STATUS_SELECT[$row->status] : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.transactions.index');
    }

    public function edit(Transaction $transaction)
    {
        abort_if(Gate::denies('transaction_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $banks = Bank::pluck('bank_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $transaction->load('bank');
        return view('admin.transactions.edit', compact('banks', 'transaction'));
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        DB::transaction(function () use ($request, $transaction) {

            $transaction_type = $transaction->transaction_type;
            $transaction_status = $transaction->status;
            $transaction_bank_id = $transaction->bank_id;
            $transaction_amount = $transaction->amount;

            $request_bank_id = $request->bank_id;
            $request_amount = $request->amount;
            $status = $request->status;


            $bank = Bank::find($transaction_bank_id);

            // undo old operation
            if($transaction_status=="Approved" || $transaction_status=="Pending"){
                if($transaction_type=="Withdrawal"){
                    $bank->balance = $bank->balance + $transaction_amount;
                }else if($transaction_type=="Deposit"){
                    $bank->balance = $bank->balance - $transaction_amount;
                }
                $bank->save();
            }

            // do new operation
            if($status=="Approved" || $status=="Pending"){
                $bank = Bank::find($request_bank_id);
                if($transaction_type=="Withdrawal"){
                    $bank->balance = $bank->balance - $request_amount;
                }else if($transaction_type=="Deposit"){
                    $bank->balance = $bank->balance + $request_amount;
                }
                $bank->save();
            }

            if($status=="Approved" || $status=="Void"){
                $request->request->add(['approver_id' => Auth::id()]);
                $request->request->add(['approve_datetime' => now()]);
            }

            $transaction->update($request->all());
        });

        return redirect()->route('admin.transactions.index');
    }

    public function show(Transaction $transaction)
    {
        abort_if(Gate::denies('transaction_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $transaction->load('bank', 'entry_user', 'approver');

        return view('admin.transactions.show', compact('transaction'));
    }

    public function destroy(Transaction $transaction)
    {
        abort_if(Gate::denies('transaction_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = Auth::user();
        $roles = $user->roles()->pluck('title')->toArray();

        $group = $user->group;
        $banks = $group->banks->pluck('id')->toArray();

        if(in_array($transaction->bank_id, $banks)){

            DB::transaction(function () use ($transaction){

                $transaction_type = $transaction->transaction_type;
                $amount = $transaction->amount;
                $bank_id = $transaction->bank_id;

                $bank = Bank::find($bank_id);
                if($transaction_type=="Withdrawal"){
                    $bank->balance = $bank->balance + $amount;
                }else if($transaction_type=="Deposit"){
                    $bank->balance = $bank->balance - $amount;
                }

                $bank->save();
                $transaction->delete();

            });
        }else{
            Session::flash('message', 'You are not allowed to delete this transaction. ');
            return view('admin.transactions.index');
        }

        return back();

    }
}

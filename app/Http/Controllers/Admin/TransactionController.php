<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTransactionRequest;
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

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('transaction_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Transaction::with(['bank', 'entry_user', 'approver'])->select(sprintf('%s.*', (new Transaction())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'transaction_show';
                $editGate = 'transaction_edit';
                $deleteGate = 'transaction_delete';
                $crudRoutePart = 'transactions';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('transaction_type', function ($row) {
                return $row->transaction_type ? Transaction::TRANSACTION_TYPE_RADIO[$row->transaction_type] : '';
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
            $table->editColumn('status', function ($row) {
                return $row->status ? Transaction::STATUS_SELECT[$row->status] : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.transactions.index');
    }

    public function create()
    {
        abort_if(Gate::denies('transaction_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $banks = Bank::pluck('bank_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.transactions.create', compact('banks'));
    }

    public function store(StoreTransactionRequest $request)
    {

        $transaction_type = $request->transaction_type;
        $bank_id = $request->bank_id;
        $amount = $request->amount;

        $bank = Bank::find($bank_id);
        if($transaction_type=="Withdrawal"){
            $request->request->add(['status' => 'Approved']);
            $bank->balance = $bank->balance - $amount;
        }else if($transaction_type=="Deposit"){
            $request->request->add(['status' => 'Pending']);
            $bank->balance = $bank->balance + $amount; 
        }

        $bank->save();

        $transaction = Transaction::create($request->all());

        return redirect()->route('admin.transactions.index');
    }

    public function edit(Transaction $transaction)
    {
        abort_if(Gate::denies('transaction_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $banks = Bank::pluck('bank_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $approvers = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $transaction->load('bank', 'entry_user', 'approver');

        return view('admin.transactions.edit', compact('approvers', 'banks', 'transaction'));
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
                // undo old operation 
        $transaction_type = $transaction->transaction_type;
        $bank_id = $transaction->bank_id;
        $amount = $transaction->amount;
        $status = $request->status;

        $bank = Bank::find($bank_id);
        if($transaction_type=="Withdrawal"){
            $bank->balance = $bank->balance + $amount;
        }else if($transaction_type=="Deposit"){
            $bank->balance = $bank->balance - $amount; 
        }
        $bank->save();

        // do new operation 
        if($status=="Approved" || $status=="Pending"){
            $transaction_type = $request->transaction_type;
            $bank_id = $request->bank_id;
            $amount = $request->amount;

            $bank = Bank::find($bank_id);
            if($transaction_type=="Withdrawal"){
                $bank->balance = $bank->balance - $amount;
            }else if($transaction_type=="Deposit"){
                $bank->balance = $bank->balance + $amount; 
            }

            $bank->save();
        }

        if($status=="Approved" || $status=="Void"){
            $request->request->add(['approver' => Auth::user()->name]);
            $request->request->add(['approve_datetime' => now()]);
        }

            $transaction->update($request->all());

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

        return back();
    }

    public function massDestroy(MassDestroyTransactionRequest $request)
    {
        foreach(request('ids') as $id){
            $transaction = Transaction::find($id);
            $transaction_type = $transaction->transaction_type;
            $amount = $transaction->amount;
            $bank_id = $transaction->bank_id;

            $bank = Bank::find($bank_id);
            $bank = Bank::find($bank_id);

            if($transaction_type=="Withdrawal"){
                $bank->balance = $bank->balance + $amount;
            }else if($transaction_type=="Deposit"){
                $bank->balance = $bank->balance - $amount; 
            }

            $bank->save();
            $transaction->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}

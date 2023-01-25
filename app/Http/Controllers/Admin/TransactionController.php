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
                $query = Transaction::whereIn('bank_id', $banks)->with(['bank', 'entry_user', 'approver'])->select(sprintf('%s.*', (new Transaction())->table));
            } else if(in_array('Approver', $roles)){
                $country = $user->country;
                $banks = $user->country->countryBanks->pluck('id')->toArray();
                $query = Transaction::whereIn('bank_id', $banks)->with(['bank', 'entry_user', 'approver'])->select(sprintf('%s.*', (new Transaction())->table));
            }else{
                $query = Transaction::with(['bank', 'entry_user', 'approver'])->select(sprintf('%s.*', (new Transaction())->table));
            }
            
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'transaction_show';
                $editGate = 'transaction_edit';
                $deleteGate = 'transaction_delete';
                $crudRoutePart = 'transactions';

                if($row->status == "Approved" || $row->status == "Void"){
                    return view('partials.datatablesReadOnlyActions', compact(
                        'viewGate',
                        'crudRoutePart',
                        'row'
                    ));
                }else{
                    return view('partials.datatablesActions', compact(
                        'viewGate',
                        'editGate',
                        'deleteGate',
                        'crudRoutePart',
                        'row'
                    ));
                }
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

        $user = Auth::user();
        $roles = $user->roles()->pluck('title')->toArray();


        if(in_array('Entry Person', $roles)){
            $banks = Bank::where('group_id', $user->group_id)->pluck('bank_name', 'id')->prepend(trans('global.pleaseSelect'), '');
        }else{
            $banks = Bank::pluck('bank_name', 'id')->prepend(trans('global.pleaseSelect'), '');
        }

        return view('admin.transactions.create', compact('banks'));
    }

    public function store(StoreTransactionRequest $request)
    {
        return DB::transaction(function () use ($request){

            $transaction_type = $request->transaction_type;
            $bank_id = $request->bank_id;
            $amount = $request->amount;

            if($transaction_type=="Withdrawal"){
                if(BankController::validTransaction($bank_id, $amount)){

                    Session::flash('message', 'Bank balance must not be less than zero. ');
                    return view('admin.transactions.index');
                }
            }

            $bank = Bank::find($bank_id);
            if($transaction_type=="Withdrawal"){
                $request->request->add(['status' => 'Approved']);
                $bank->balance = $bank->balance - $amount;
            }else if($transaction_type=="Deposit"){
                $request->request->add(['status' => 'Pending']);
                $bank->balance = $bank->balance + $amount; 
            }
            $bank->save();
    
            $request->request->add(['entry_user_id' => Auth::id()]);
            $request->request->add(['entry_datetime' => now()]);
    
            $transaction = Transaction::create($request->all());
            
            return redirect()->route('admin.transactions.index'); 
        });

        
    }

    public function edit(Transaction $transaction)
    {
        abort_if(Gate::denies('transaction_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = Auth::user();
        $roles = $user->roles()->pluck('title')->toArray();
        
            if(in_array('Entry Person', $roles)){
                $group = $user->group;
                $banks = $group->banks->pluck('id')->toArray();
                if(in_array($transaction->bank_id, $banks)){
                    $banks = Bank::whereIn('id', $banks)->pluck('bank_name', 'id')->prepend(trans('global.pleaseSelect'), '');
                }else{
                    Session::flash('message', 'You are not allowed to edit this transaction. ');
                    return view('admin.transactions.index');
                }
            }else if(in_array('Approver', $roles)){
                $country = $user->country;
                $banks = $user->country->countryBanks->pluck('id')->toArray();
                if(in_array($transaction->bank_id, $banks)){
                $banks = Bank::whereIn('id', $banks)->pluck('bank_name', 'id')->prepend(trans('global.pleaseSelect'), '');
                }else{
                    Session::flash('message', 'You are not allowed to edit this transaction. ');
                    return view('admin.transactions.index');
                }
            } else{
                $banks = Bank::pluck('bank_name', 'id')->prepend(trans('global.pleaseSelect'), '');
            }
    
            $transaction->load('bank');
            return view('admin.transactions.edit', compact('banks', 'transaction'));
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        DB::transaction(function () use ($request, $transaction) {
              
            $transaction_type = $transaction->transaction_type;
            $transaction_bank_id = $transaction->bank_id;
            $transaction_amount = $transaction->amount;

            $request_bank_id = $request->bank_id;
            $request_amount = $request->amount;
            $status = $request->status;

            
            $bank = Bank::find($transaction_bank_id);

            // make sure the bank balance is not less than zero
            if((BankController::validTransaction($request_bank_id, $transaction_amount) && ($request_amount-$bank->balance)<0)){
                $banks = Bank::pluck('bank_name', 'id')->prepend(trans('global.pleaseSelect'), '');
                $transaction->load('bank');

                Session::flash('message', 'Bank balance must not be less than zero. ');
                return view('admin.transactions.edit', compact('banks', 'transaction'));
            }

            // undo old operation
            if($transaction_type=="Withdrawal"){
                $bank->balance = $bank->balance + $transaction_amount;
            }else if($transaction_type=="Deposit"){
                $bank->balance = $bank->balance - $transaction_amount; 
            }
            $bank->save();

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

                if($transaction_type=="Deposit"){
                    if(BankController::validTransaction($bank_id, $amount)){
                        Session::flash('message', 'Bank balance must not be less than zero. ');
                        return back();
                    }
                }
                
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

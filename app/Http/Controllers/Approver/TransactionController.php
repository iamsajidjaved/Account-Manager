<?php

namespace App\Http\Controllers\Approver;

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

class TransactionController extends Controller {
    public function index( Request $request, $bank_id ) {
        abort_if ( Gate::denies( 'transaction_edit' ), Response::HTTP_FORBIDDEN, '403 Forbidden' );

        $user = Auth::user();
        $roles = $user->roles()->pluck( 'title' )->toArray();

        if ( in_array( 'Approver', $roles ) ) {
            $country = $user->country;
            $banks = $user->country->countryBanks->pluck( 'id' )->toArray();
            $transactions = Transaction::whereIn( 'bank_id', $banks )->where( 'transaction_type', 'Deposit' )->where( 'status', 'Pending' )->latest()->get();
            return view( 'approver.index', compact( 'transactions', 'bank_id' ) );
        }
    }

    public function update( Request $request ) {
        if ( $request->ajax() ) {

            $name = $request->name;
            $value = $request->value;

            $transaction = Transaction::find( $request->pk );

            if ( $name == 'status' ) {
                if ( $transaction->deposit_no == '' ) {
                    return response()->json( [ 'success' => false ] );
                }

                $bank = $transaction->bank;
                $transaction_status = $transaction->status;
                $transaction_type = $transaction->transaction_type;
                $transaction_amount = $transaction->amount;

                if ( $transaction_status == 'Approved' ) {
                    if ( $value == 'Void' ) {
                        $bank->balance = $bank->balance - $transaction_amount;
                        $bank->save();
                    }
                } else if ( $transaction_status == 'Void' ) {
                    if ( $value == 'Approved' || $value == 'Pending' ) {
                        $bank->balance = $bank->balance + $transaction_amount;
                        $bank->save();
                    }
                } else if ( $transaction_status == 'Pending' ) {
                    if ( $value == 'Void' ) {
                        $bank->balance = $bank->balance - $transaction_amount;
                        $bank->save();
                    }
                }
            }

            $transaction->$name = $value;
            $transaction->save();

            return response()->json( [ 'success' => true ] );
        }
    }
}

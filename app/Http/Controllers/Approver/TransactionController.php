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
    public function index( Request $request ) {
        abort_if ( Gate::denies( 'transaction_edit' ), Response::HTTP_FORBIDDEN, '403 Forbidden' );

        $user = Auth::user();
        $country = $user->country;
        $banks = $user->country->countryBanks->pluck( 'id' )->toArray();

        $pending_transactions = Transaction::whereIn( 'bank_id', $banks )->where( 'transaction_type', 'Deposit' )->where( 'status', 'Pending' )->get();
        $recent_approved_transactions = Transaction::whereIn( 'bank_id', $banks )->where( 'transaction_type', 'Deposit' )->where( 'status', 'Approved' )->orderBy( 'updated_at', 'desc' )->take( 10 )->get();

        return view( 'approver.transactions.index', compact( 'pending_transactions', 'recent_approved_transactions', 'country' ) );
    }

    public function update( Request $request ) {

        if ( $request->ajax() ) {

            $name = $request->name;
            $value = $request->value;

            $user = Auth::user();
            $country = $user->country;
            $banks = $user->country->countryBanks->pluck( 'id' )->toArray();

            $transaction = Transaction::find( $request->pk );
            $bank = $transaction->bank;

            if ( !in_array( $bank->id, $banks ) ) {
                return;
            }

            if ( $value != '' ) {
                $transaction->status = 'Approved';
            } else {
                $transaction->status = 'Pending';
            }

            $transaction->$name = $value;
            $transaction->save();

            return response()->json( [ 'success' => true, 'pk' => $request->pk ] );
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyBankRequest;
use App\Http\Requests\StoreBankRequest;
use App\Http\Requests\UpdateBankRequest;
use App\Models\Bank;
use App\Models\Country;
use App\Models\Group;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class BankController extends Controller {
    public function index() {
        abort_if ( Gate::denies( 'bank_access' ), Response::HTTP_FORBIDDEN, '403 Forbidden' );

        $user = Auth::user();
        $roles = $user->roles()->pluck( 'title' )->toArray();

        if ( in_array( 'Entry Person', $roles ) ) {
            $group = $user->group;
            $banks = $group->banks->pluck( 'id' )->toArray();
            $query = Bank::whereIn( 'id', $banks )->with( [ 'country', 'group' ] )->get();
        } else if ( in_array( 'Approver', $roles ) ) {
            $country = $user->country;
            $banks = $user->country->countryBanks->pluck( 'id' )->toArray();
            $query = Bank::whereIn( 'id', $banks )->with( [ 'country', 'group' ] )->get();
        } else {
            $banks = Bank::with( [ 'country', 'group' ] )->get();
        }

        $banks = Bank::with( [ 'country', 'group' ] )->get();

        return view( 'admin.banks.index', compact( 'banks', 'roles' ) );
    }

    public function create() {
        abort_if ( Gate::denies( 'bank_create' ), Response::HTTP_FORBIDDEN, '403 Forbidden' );

        $countries = Country::pluck( 'name', 'id' )->prepend( trans( 'global.pleaseSelect' ), '' );

        $groups = Group::pluck( 'group_name', 'id' )->prepend( trans( 'global.pleaseSelect' ), '' );

        return view( 'admin.banks.create', compact( 'countries', 'groups' ) );
    }

    public function store( StoreBankRequest $request ) {
        $bank = Bank::create( $request->all() );

        return redirect()->route( 'admin.banks.index' );
    }

    public function edit( Bank $bank ) {
        abort_if ( Gate::denies( 'bank_edit' ), Response::HTTP_FORBIDDEN, '403 Forbidden' );

        $countries = Country::pluck( 'name', 'id' )->prepend( trans( 'global.pleaseSelect' ), '' );

        $groups = Group::pluck( 'group_name', 'id' )->prepend( trans( 'global.pleaseSelect' ), '' );

        $bank->load( 'country', 'group' );

        return view( 'admin.banks.edit', compact( 'bank', 'countries', 'groups' ) );
    }

    public function update( UpdateBankRequest $request, Bank $bank ) {
        $bank->update( $request->all() );

        return redirect()->route( 'admin.banks.index' );
    }

    public function show( Bank $bank ) {
        abort_if ( Gate::denies( 'bank_show' ), Response::HTTP_FORBIDDEN, '403 Forbidden' );

        $bank->load( 'country', 'group' );

        return view( 'admin.banks.show', compact( 'bank' ) );
    }

    public function destroy( Bank $bank ) {
        abort_if ( Gate::denies( 'bank_delete' ), Response::HTTP_FORBIDDEN, '403 Forbidden' );

        $bank->delete();

        return back();
    }

    public function massDestroy( MassDestroyBankRequest $request ) {
        Bank::whereIn( 'id', request( 'ids' ) )->delete();

        return response( null, Response::HTTP_NO_CONTENT );
    }

    public static function validTransaction( $bank_id, $amount ) {

        $bank = Bank::find( $bank_id );
        $balance = $bank->balance;

        if ( ( $balance-$amount ) < 0 ) {
            return true;
        } else {
            return false;
        }
    }
}

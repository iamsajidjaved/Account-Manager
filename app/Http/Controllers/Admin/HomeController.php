<?php

namespace App\Http\Controllers\Admin;

use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use Illuminate\Support\Facades\Auth;
use App\Models\Bank;

class HomeController {
    public function index() {
        $user = Auth::user();
        $roles = $user->roles()->pluck( 'title' )->toArray();

        if ( in_array( 'Entry Person', $roles ) ) {
            $group = $user->group;
            $bank_ids = $group->banks->pluck( 'id' )->toArray();
            $banks = Bank::whereIn( 'id', $bank_ids )->with( [ 'country', 'group' ] )->get();

            return view( 'entry_person.home', compact( 'banks' ) );
        } else if ( in_array( 'Approver', $roles ) ) {
            $country = $user->country;

            return view( 'approver.home', compact( 'country' ) );
        }

        return view( 'home' );
    }
}

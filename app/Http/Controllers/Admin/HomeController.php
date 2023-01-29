<?php

namespace App\Http\Controllers\Admin;

use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use Illuminate\Support\Facades\Auth;

class HomeController {
    public function index() {
        return view( 'home' );
    }
}

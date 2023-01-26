<?php

namespace App\Http\Controllers\Admin;

use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class HomeController
 {
    public function index()
 {
        $settings1 = [
            'chart_title'           => 'Latest Approved Transactions',
            'chart_type'            => 'latest_entries',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\Transaction',
            'group_by_field'        => 'entry_datetime',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'group_by_field_format' => 'Y-m-d H:i:s',
            'column_class'          => 'col-md-12',
            'entries_number'        => '5',
            'fields'                => [
                'customer_name' => '',
                'amount'        => '',
                'status'        => '',
                'bank'          => 'bank_name',
                'entry_user'    => 'name',
            ],
            'translation_key' => 'transaction',
        ];

        $settings1[ 'data' ] = [];
        if ( class_exists( $settings1[ 'model' ] ) ) {
            $settings1[ 'data' ] = $settings1[ 'model' ]::latest()
            ->take( $settings1[ 'entries_number' ] )
            ->where('status', 'Approved')
            ->get();
        }

        if ( !array_key_exists( 'fields', $settings1 ) ) {
            $settings1[ 'fields' ] = [];
        }

        $settings2 = [
            'chart_title'           => 'Latest Pending Transactions',
            'chart_type'            => 'latest_entries',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\Transaction',
            'group_by_field'        => 'entry_datetime',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'group_by_field_format' => 'Y-m-d H:i:s',
            'column_class'          => 'col-md-12',
            'entries_number'        => '10',
            'fields'                => [
                'customer_name' => '',
                'amount'        => '',
                'status'        => '',
                'bank'          => 'bank_name',
                'approver'      => 'name',
            ],
            'translation_key' => 'transaction',
        ];

        $settings2[ 'data' ] = [];
        if ( class_exists( $settings2[ 'model' ] ) ) {
            $settings2[ 'data' ] = $settings2[ 'model' ]::latest()
            ->take( $settings2[ 'entries_number' ] )
            ->where('status', 'Pending')
            ->get();
        }

        if ( !array_key_exists( 'fields', $settings2 ) ) {
            $settings2[ 'fields' ] = [];
        }

        return view( 'home', compact( 'settings1', 'settings2' ) );
    }
}

<?php

namespace App\Http\Controllers\Admin;

use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class HomeController
{
    public function index()
    {
        $settings1 = [
            'chart_title'           => 'Latest Transaction',
            'chart_type'            => 'latest_entries',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\Transaction',
            'group_by_field'        => 'void_datetime',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'group_by_field_format' => 'Y-m-d H:i:s',
            'column_class'          => 'col-md-12',
            'entries_number'        => '10',
            'fields'                => [
                'customer_name' => '',
                'amount'        => '',
                'reference'     => '',
                'bank'          => 'bank_name',
            ],
            'translation_key' => 'transaction',
        ];

        $settings1['data'] = [];
        if (class_exists($settings1['model'])) {
            $settings1['data'] = $settings1['model']::latest()
                ->take($settings1['entries_number'])
                ->get();
        }

        if (!array_key_exists('fields', $settings1)) {
            $settings1['fields'] = [];
        }

        return view('home', compact('settings1'));
    }
}

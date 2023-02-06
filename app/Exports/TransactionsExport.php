<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionsExport implements FromQuery, ShouldAutoSize, WithHeadings {
    use Exportable;

    protected $bank_id;
    protected $from;
    protected $to;

    public function __construct( $bank_id, $from, $to ) {
        $this->bank_id = $bank_id ;
        $this->from = date( 'Y-m-d', strtotime( $from ) );
        $this->to = date( 'Y-m-d', strtotime( $to ) );
    }

    public function query() {
        return Transaction::query()
        ->select( 'created_at', 'customer_name', 'amount', 'transaction_type', 'reference', 'deposit_no', 'status', 'remarks' )
        ->where( 'bank_id', $this->bank_id )
        ->whereNot( 'status', 'Void' )
        ->whereBetween( 'created_at', array( $this->from, $this->to ) );
    }

    public function headings(): array {
        return [ 'Date', 'Customer Name', 'Amount', 'Transaction Type', 'Reference', 'Deposit No', 'Status', 'Purpose' ];
    }
}

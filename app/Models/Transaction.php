<?php

namespace App\Models;

use \DateTimeInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;
    use HasFactory;

    public const TRANSACTION_TYPE_RADIO = [
        'Deposit'    => 'Deposit',
        'Withdrawal' => 'Withdrawal',
    ];

    public const STATUS_SELECT = [
        'Pending'  => 'Pending',
        'Approved' => 'Approved',
        'Void'     => 'Void',
    ];

    public $table = 'transactions';

    protected $dates = [
        'entry_datetime',
        'approve_datetime',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'customer_name',
        'amount',
        'bank_id',
        'reference',
        'status',
        'beneficiary_bank',
        'withdraw_purpose',
        'entry_user_id',
        'entry_datetime',
        'transaction_type',
        'deposit_no',
        'approver_id',
        'approver_remarks',
        'approve_datetime',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function entry_user()
    {
        return $this->belongsTo(User::class, 'entry_user_id');
    }

    public function getEntryDatetimeAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setEntryDatetimeAttribute($value)
    {
        $this->attributes['entry_datetime'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function getApproveDatetimeAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setApproveDatetimeAttribute($value)
    {
        $this->attributes['approve_datetime'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}

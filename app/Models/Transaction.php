<?php

namespace App\Models;

use \DateTimeInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Transaction extends Model implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;
    use HasFactory;

    public const STATUS_SELECT = [
        'Approved' => 'Approved',
        'Void'     => 'Void',
    ];

    public const TRANSACTION_TYPE_RADIO = [
        'Deposit'    => 'Deposit',
        'Withdrawal' => 'Withdrawal',
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
        'transaction_type',
        'customer_name',
        'amount',
        'bank_id',
        'reference',
        'status',
        'entry_user_id',
        'entry_datetime',
        'deposit_no',
        'approver_id',
        'approver_remarks',
        'approve_datetime',
        'remarks',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

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

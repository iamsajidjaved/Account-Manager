<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model {
    use SoftDeletes;
    use HasFactory;

    public $table = 'banks';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'bank_name',
        'balance',
        'country_id',
        'group_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function country() {
        return $this->belongsTo( Country::class, 'country_id' );
    }

    public function group() {
        return $this->belongsTo( Group::class, 'group_id' );
    }

    public function transactions() {
        return $this->hasMany( Transaction::class, 'bank_id' );
    }

    protected function serializeDate( DateTimeInterface $date ) {
        return $date->format( 'Y-m-d H:i:s' );
    }
}

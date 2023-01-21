<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;
    use HasFactory;

    public $table = 'countries';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'short_code',
        'group_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function countryBanks()
    {
        return $this->hasMany(Bank::class, 'country_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TAccountTransactionTransfer extends Model
{
    protected $table = 't_account_transaction_transfer';

    protected $fillable = [
        'id',
        'trans_code',
        'uid_account_sender',
        'trans_type',
        'nominal',
        'uid_account_destination',
        'trans_date',
        'description',
        'enabled',
        'uid',
    ];

    public $incrementing = true;
    protected $primaryKey = 'id';
    public $timestamps = true;

    public function user_sender () {
        return $this->hasOne(MAccountCustomer::class, 'uid', 'uid_account_sender');
    }

    public function user_receiver () {
        return $this->hasOne(MAccountCustomer::class, 'uid', 'uid_account_destination');
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot() {
        parent::boot();

        // Listen creating event
        self::creating(function ($model) {
            $model->uid = (string) Str::uuid();
        });
    }
}

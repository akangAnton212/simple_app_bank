<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TMutation extends Model
{
    protected $table = 't_mutation';

    protected $fillable = [
        'id',
        'uid_account',
        'mutation_date',
        'nominal',
        'trans_type',
        'trans_code',
        'balance',
        'description',
        'enabled',
        'uid',
    ];

    public $incrementing = true;
    protected $primaryKey = 'id';
    public $timestamps = true;

    public function account_user () {
        return $this->hasOne(MAccountCustomer::class, 'uid', 'uid_account');
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

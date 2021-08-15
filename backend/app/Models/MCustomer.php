<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MCustomer extends Model
{
    protected $table = 'm_customer';

    protected $fillable = [
        'id',
        'NIK',
        'name',
        'DOB',
        'POB',
        'email',
        'address',
        'province',
        'city',
        'postal_code',
        'register_date',
        'uid_register_by',
        'enabled',
        'uid',
    ];

    public $incrementing = true;
    protected $primaryKey = 'id';
    public $timestamps = true;

    public function scopeWhereCustomer($query, $search) {
        $q = '%'.urldecode($search).'%';
        return $query->where(function ($query) use ($q) {
            return $query->where('NIK', 'LIKE', $q)
                ->orWhere('name','LIKE',$q)
                ->orWhere('email','LIKE',$q);
        })
        ->orWhereHas('customer_accounts', function ($query) use ($q) {
            return $query->where('account_number', 'LIKE', $q);
        });
    }

    public function customer_accounts()
    {
        return $this->hasMany(MAccountCustomer::class, 'uid_customer', 'uid')
            ->where('enabled', true);
    }

    public function user_register () {
        return $this->hasOne(MUser::class, 'uid', 'uid_register_by');
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_name',
        'account_number',
        'ifsc_code',
        'ownerable_type',
        'ownerable_id',
        'address',
        'account_holder_name',
        'contact_id',
        'fund_account'
    ];

    public function ownerable(){
        return $this->morphTo();
    }
}

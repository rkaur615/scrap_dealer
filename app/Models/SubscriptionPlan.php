<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;
    protected $fillable = [
        'plan_id',
        'name',
        'transaction_id',
        'amount',
        'no_of_leads',
        'is_lead_carry_over',
        'added_by',
        'isSuccessfulTransaction',
    ];
}

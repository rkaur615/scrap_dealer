<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'name',
        'amount',
        'transaction_id',
        'is_lead_carry_over',
        'no_of_leads',
        'isSuccessfulTransaction',
    ];
    public function subscriptionPlan(){
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id', 'id');
    }
}

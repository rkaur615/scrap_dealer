<?php

namespace App\Models;

use App\Models\Scopes\RecyclerScope;
use App\Models\Scopes\CharitableScope;
use App\Models\Scopes\ScrapDealerScope;
use App\Models\Scopes\ServiceProviderScope;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\UserType;
use App\Models\UserRating;
use App\Models\UserCompany;
use App\Models\UserSubscription;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $perPage = 5;
    protected $appends = ['pic'];
    protected $with = ['reviews'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'verification_code',
        'image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    /*
     * The roles that belong to this user
    */
    public function roles()
    {
        return $this->belongsToMany(UserType::class);
    }

    public function types()
    {
        /*
        * Ideally it should be the Types
        */
        return $this->hasMany(UserRole::class)->with('role:id,title')->select(['user_type_id', 'user_id', 'id']);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new RecyclerScope);
        static::addGlobalScope(new CharitableScope);
        static::addGlobalScope(new ScrapDealerScope);
        static::addGlobalScope(new ServiceProviderScope);
    }

    public function role(){
        return UserRole::where('user_id', $this->id)->first();
        //return $this->hasOneThrough(UserRole::class, UserType::class,  'user_id', 'user_type_id',null, null);
    }

    public function addresses()
    {
        return $this->hasOne(UserAddress::class, 'user_id');
    }
    public function feedback()
    {
        return $this->hasMany(UserRating::class, 'supplier_id', 'id');
    }
    public function pic($id=null)
    {
        return auth()->user()?(Upload::where(['filetype'=>1, 'ref_id'=>$this->id])->first()?'/storage/'.Upload::where(['filetype'=>1, 'ref_id'=>$this->id])->first()->filepath:'/assets/images/userImg.png'):'';

        // if($id){
        //     return Upload::where(['filetype'=>1, 'ref_id'=>$id])->first()?'/storage/'.Upload::where(['filetype'=>1, 'ref_id'=>$id])->first()->filepath:'/assets/images/userImg.png';
        // }
        // else{
        //     return auth()->user()?(Upload::where(['filetype'=>1, 'ref_id'=>auth()->user()->id])->first()?'/storage/'.Upload::where(['filetype'=>1, 'ref_id'=>auth()->user()->id])->first()->filepath:'/assets/images/userImg.png'):'';
        // }

    }
    public function getPicAttribute()
    {
        // return auth()->user()?(Upload::where(['filetype'=>1, 'ref_id'=>auth()->user()->id])->first()?'/storage/'.Upload::where(['filetype'=>1, 'ref_id'=>auth()->user()->id])->first()->filepath:'/assets/images/userImg.png'):'';
        return Upload::where(['filetype'=>1, 'ref_id'=>$this->id])->first()?'/storage/'.Upload::where(['filetype'=>1, 'ref_id'=>$this->id])->first()->filepath:'/assets/images/userImg.png';

    }


    public function companies()
    {
        return $this->hasMany(UserCompany::class, 'user_id');
    }


    public function scopeRecycler($query){
        return $query->with('types')->whereHas('types', function($q){
            $q->where("user_type_id", config('constants.userRoles.Recycler'));
        });
    }

    public function scopeCharitable($query){
        return $query->with('types')->whereHas('types', function($q){
            $q->where("user_type_id", config('constants.userRoles.Charitable Organization'));
        });
    }

    public function scopeScrapDealer($query){
        return $query->with('types')->whereHas('types', function($q){
            $q->where("user_type_id", config('constants.userRoles.Scrap Dealer'));
        });
    }

    public function scopeServiceProvider($query){
        return $query->with('types')->whereHas('types', function($q){
            $q->where("user_type_id", config('constants.userRoles.Service Provider'));
        });
    }


    public function scopeRegular($query){
        return $query->with('types')->whereHas('types', function($q){
            $q->where("user_type_id", config('constants.userRoles.Customer'));
        });
    }

    public function scopeAll($query){
        return $query->with('types');
    }

    public function usersubscription(){
        return $this->hasMany(UserSubscription::class)->select(['id','user_id','subscription_plan_id']);
    }

    public function bids(){
        return $this->hasMany(ProductBidding::class,'added_by','id');
    }

    public function bankDetail(){
        return $this->morphOne(BankDetail::class, 'ownerable');
    }

    public function isProfileCompleted(){
        return $this->is_profile_completed;
    }

    public function categories()
    {
        return $this->hasMany("App\Models\UserCategory");
    }

    public function cats()
    {
        return $this->hasMany("App\Models\UserCategory");
    }

    public function supplierRequirements()
    {
        return $this->hasMany(SupplierRequirement::class, 'user_id', 'id');
    }

    public function distance($latitude, $longitude)
    {
    // Sum log records of type I (add)
    // and substract the sum of all log records of type ) (sub)
    // if (is_array($point)) {
    //     $latitude = $point['latitude'];
    //     $longitude = $point['longitude'];
    // }
    return DB::table('user_addresses')->selectRaw(
        'ST_Distance_Sphere(point(`longitude`, `latitude`), point(?, ?))/1000 AS distance',
        [$longitude, $latitude]
    )

        ->where('user_id', $this->id);
    }


    public function scopeClosestTo($query, $point)
    {
        if (is_array($point)) {
            $latitude = $point['latitude'];
            $longitude = $point['longitude'];
        }
        if (isset($latitude) && isset($longitude)) {
            return $query
            // ->having('distance', '<', 100)
            ->orderBy(function ($address) use ($point) {
            if (is_array($point)) {
                $latitude = $point['latitude'];
                $longitude = $point['longitude'];
            }

            if (isset($latitude) && isset($longitude)) {
                return $address->selectRaw(
                    'ST_Distance_Sphere(point(`longitude`, `latitude`), point(?, ?))/1000 AS distance',
                    [$longitude, $latitude]
                )
                    ->from('user_addresses')
                    ->whereColumn('user_id', 'users.id')

                    ->orderBy('distance');
            }
        });
        }


    }

    public function userRole()
    {
        return $this->hasOne(UserRole::class);
    }


    public function messages(){
        return $this->hasMany(Message::class);
    }


    public function reviews(){
        return $this->hasMany(UserRating::class, 'supplier_id', 'id');
    }

    public function scopeSuppliers(Builder $query, $name){
        return $query->with('types')->whereHas('types', function($q){
            $q->where("user_type_id", config('constants.userRoles.supplier'));
        })->where('name','LIKE','%'.$name.'%');
    }

    public function scopeSupplier($query){
        return $query->with('types')->whereHas('types', function($q){
            $q->where("user_type_id", config('constants.userRoles.supplier'));
        });
    }
    

    public function catalog(){
        return $this->hasMany(UserCatalog::class, 'user_id', 'id');

    }

}

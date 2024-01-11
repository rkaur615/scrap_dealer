<?php

namespace App\View\Components;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\Component;

class SupplierList extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $suppliers;
    public $myAddress;
    public $showInvite = false;

    public function __construct(Request $request)
    {
        //
        $retailer = auth()->id();

        if($request->has('invite') && $request->invite =='true'){
                $this->showInvite = true;
        }
        $this->myAddress = $myAddress = User::find(auth()->user()->id)->addresses;
        $myLat = $myAddress['latitude'];
        $myLong = $myAddress['longitude'];
        if($request->has('search')){


            $this->suppliers = User::query()->with(['addresses', 'categories.category', 'supplierRequirements'])->suppliers($request->get('search'))->closestTo(['latitude'=>$myLat,'longitude'=>$myLong],100)->get();

        }
        else{
            $this->suppliers = User::query()->with(['addresses', 'categories.category', 'supplierRequirements'])->supplier()->closestTo(['latitude'=>$myLat,'longitude'=>$myLong],100)->get();
        }


    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.supplier-list',['suppliers'=>$this->suppliers, 'showInvite'=>$this->showInvite]);
    }
}

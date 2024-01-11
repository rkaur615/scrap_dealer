<?php

namespace App\View\Components;

use App\Models\Requirement;
use Illuminate\Http\Request;
use Illuminate\View\Component;

class RequirementList extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $requirements;
    public function __construct(Request $request)
    {
        //
        if($request->has('search')){
            $this->requirements = Requirement::query()->with(['retailer', 'retailer.addresses'])->where('title','Like',('%'.$request->get('search').'%'))->where('status',1)->get();
        }
        else{
            $this->requirements = Requirement::with('retailer', 'retailer.addresses')->where('status',1)->get();
        }

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.requirement-list', ['requirements'=>$this->requirements]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\RequirementsResurce;
use App\Models\Category;
use App\Models\GeneralSetting;
use App\Models\ProductTitle;
use App\Models\Requirement;
use App\Models\RequirementItem;
use App\Models\SupplierQuote;
use App\Models\SupplierRequirement;
use App\Models\User;
use App\Models\UserRating;
use App\Notifications\InviteReceived;
use App\Notifications\QuoteApproved;
use App\Notifications\QuoteCOmpleted;
use App\Notifications\QuoteReceived;
use App\Notifications\QuoteRejected;
use App\Notifications\QuoteUpdated;
use Illuminate\Http\Request;

class RequirementController extends Controller
{
    //

    public function list(Request $request){

        $requirements = Requirement::where('user_id', auth()->user()->id)->latest()->get();
        if($request->ajax()){
            return response()->json(['requirements'=>$requirements,'id'=>auth()->user()->id]);
        }
        $settings = GeneralSetting::all();
        return view('site.requirement.list', compact('requirements', 'settings'));
    }

    public function rlist(Request $request, $id){

        $requirements = Requirement::where('user_id', $id)->paginate(20);
        if($request->ajax()){
            return RequirementsResurce::collection($requirements);
            return response()->json(['requirements'=>$requirements,'id'=>auth()->user()->id]);
        }
        return view('site.requirement.list', compact('requirements'));
    }

    public function edit(Request $request, $rid){
        $this->authorize('update',Requirement::find($rid));
        return view('site.requirement.add', compact('rid'));
    }

    public function add(){
        return view('site.requirement.add');
    }

    public function delete($id){
        Requirement::where(['id'=>$id, 'user_id'=>auth()->user()->id])->delete();
        return redirect()->back()->withSuccess("Requirement deleted successfully");

    }


    public function view(Request $request, $requirementId){

        return view('site.requirement.view', compact('requirementId'));
    }

    public function viewQuotes(Request $request, $requirementId){
        $this->authorize('update',Requirement::find($requirementId));
        $requirement = Requirement::with('products','retailer','retailer.addresses','quotes')->find($requirementId);
        $settings = GeneralSetting::all();
        return view('site.requirement.quotes', compact('requirement','settings'));
    }

    public function viewQuote(Request $request, $quoteId){

        $quote = SupplierRequirement::find($quoteId);
        $this->authorize('view',$quote);
        // $requirement = Requirement::with('products','retailer','retailer.addresses','quotes')->find($quote->requirement_id);
        // dd($quote);
        return view('site.requirement.quote', ['rid'=>$quote->requirement_id,'qid'=>$quote->id]);
    }

    public function getRequirement(Request $request, $requirementId){
        $requirement = Requirement::with(['products','retailer','retailer.addresses','myquote'=>function($q){
            return $q->where('user_id',auth()->user()->id)->first();
        }, 'myquote.squotes'])->find($requirementId);
        $supplierId = auth()->user()->id;
        $srData = SupplierRequirement::where(['user_id'=>$supplierId, 'requirement_id'=>$requirementId])->first();
        if($srData){
            $supplierRId = $srData->id;
            $items = collect($requirement->qitems)->map(function($item) use($supplierRId){
                $item->title = ProductTitle::find($item->code)->title;
                $sqData = SupplierQuote::where(['requirement_item_id'=>$item->id, 'supplier_requirement_id'=>$supplierRId])->first();
                $item->status = $sqData?$sqData->status:-1;
                return $item;
            });
        }
        else{

            $items = collect($requirement->qitems)->map(function($item){
                $item->title = ProductTitle::find($item->code)->title;
                $item->status = -1;
                return $item;
            });
        }


        $categories = Category::whereIn('id', $requirement->categories)->get();
        $myDetails = User::with('addresses','addresses.country','addresses.city','addresses.city', 'userRole')->find(auth()->user()->id);
        $settings = GeneralSetting::all();
        $uDetails = User::with('addresses','addresses.country','addresses.city','addresses.city','userRole')->find(auth()->user()->id);
        return response()->json(['data'=>['requirement'=>$requirement,'items'=>$items, 'my_detials'=>$myDetails, 'categories'=>$categories, 'settings'=>$settings, 'u_details'=>$uDetails]]);
    }
    public function getRequirementQuote(Request $request, $requirementId, $qid){
        $requirement = Requirement::with(['products','retailer','retailer.addresses','myquote'=>function($q)use($qid) {
            return $q->where('id',$qid)->first();
        }])->find($requirementId);


        $items = collect($requirement->qitems)->map(function($item) use($qid){
            $quote = SupplierQuote::where(['requirement_item_id'=>$item->id, 'supplier_requirement_id'=>$qid])->first();
            if($quote){
                $item->title = ProductTitle::find($item->code)->title;
                $item->status = $quote->status;
                $item->sqid = $quote->id;
            }
            else{
                $item->status = -1;
            }

            return $item;
        });
        $settings = GeneralSetting::all();
        $categories = Category::whereIn('id', $requirement->categories)->get();
        $myDetails = User::with('addresses','addresses.country','addresses.city','addresses.city','userRole')->find(SupplierRequirement::find($qid)->user_id);
        $uDetails = User::with('addresses','addresses.country','addresses.city','addresses.city','userRole')->find(auth()->user()->id);

        return response()->json(['data'=>['requirement'=>$requirement,'items'=>$items,'categories'=>$categories, 'my_detials'=>$myDetails, 'u_details'=>$uDetails, 'settings'=>$settings]]);
    }

    public function create(Request $request){
        $data = $request->all();
        $dataToBeSaved['title'] = $data['title'];
        $dataToBeSaved['expected_date'] = $data['date'];
        $dataToBeSaved['notes'] = $data['note'];
        $dataToBeSaved['items'] = $data['items'];

        $dataToBeSaved['categories'] = $data['category'];
        $dataToBeSaved['user_id'] = auth()->user()->id;


        $requirement = Requirement::create($dataToBeSaved);
        if($requirement){
            $updatedItems = collect($data['items'])->map(function($item) use($requirement) {
                unset($item['id']);
                $item['requirement_id'] = $requirement->id;
                return $item;
            })->toArray();
            RequirementItem::insert($updatedItems);
        }

        session()->flash('success','Requirement added Successfully');
        //Save Categories

        // $ = collect($data['category'])->map(fn($cat)=>['category_id'=>$cat, 'requirement_id'])
        return response()->json($data);
    }

    public function update(Request $request, $requirement){

        $data = $request->all();
        $dataToBeSaved['title'] = $data['title'];
        $dataToBeSaved['expected_date'] = $data['date'];
        $dataToBeSaved['notes'] = $data['note'];
        $items = $dataToBeSaved['items'] = $data['items'];
        $dataToBeSaved['categories'] = $data['category'];
        $dataToBeSaved['user_id'] = auth()->user()->id;
        // $requirement = Requirement::create($dataToBeSaved);
        $data = Requirement::where('id', $requirement)->update($dataToBeSaved);
        $myReq = Requirement::where('id', $requirement);

        $sqItems = collect($items)->map(function($item) use($requirement){




            $item['requirement_id'] = $requirement;

            return $item;
        });
        // $sqItems = collect($sqItems);
        // dd($sqItems);
        $posted = collect($sqItems->pluck('code'));
        $existed = collect($myReq->first()->qitems->pluck('code'));
        $itemsToBeUpdated = $posted->intersect($existed);
        $itemsToBeRemoved = $existed->diff($posted);
        $itemsToBeCreated = $posted->diff($existed);

        if($itemsToBeRemoved->count()){
            $records = $myReq->first()->qitems()->whereIn('code',$itemsToBeRemoved->toArray())->delete();
            // dd(['itemsToBeRemoved', $itemsToBeRemoved->toArray(),$records, $existed, $posted]);

            // dd($existed->diff($posted));
        }
        if($itemsToBeCreated->count()){
            $idsToBeSaved = $itemsToBeCreated->toArray();
            $itemsToBeSaved = collect($sqItems)->filter(function($itm) use($idsToBeSaved) { return in_array($itm['code'],$idsToBeSaved); })->map(function($item){ $item['status'] = 0; return $item;});

            $myReq->first()->qitems()->createMany($itemsToBeSaved);
            // dd(['itemsToBeCreated',$itemsToBeSaved, $idsToBeSaved, $items]);
            // //$records = $myReq->first()->qitems()->whereIn('code',$itemsToBeCreated->toArray())->delete();

            // dd($posted->diff($existed));
        }

        if($itemsToBeUpdated->count()){
            $idsToBeUpdated = $itemsToBeUpdated->toArray();
            $itemsToBeSaved = collect($sqItems)
                ->filter(function($itm) use($idsToBeUpdated) { return in_array($itm['code'],$idsToBeUpdated); })
                ->each(function($itm){ RequirementItem::where(['requirement_id'=>$itm['requirement_id'], 'code'=>$itm['code']])->update(['quantity'=>$itm['quantity'],'unit'=>$itm['unit']]);});



            // dd('itemsToBeUpdated');
            #ITEMS TO BE UPDATED
            // dd($itemsToBeUpdated);

        }



        // $updatedItems = collect($data['items'])->map(function($item) use($requirement) {
        //     unset($item['id']);
        //     $item['requirement_id'] = $requirement->id;
        //     return $item;
        // })->toArray();
        // RequirementItem::insert($updatedItems);

        session()->flash('success','Requirement updated Successfully');
        //Save Categories

        // $ = collect($data['category'])->map(fn($cat)=>['category_id'=>$cat, 'requirement_id'])
        return response()->json($data);
    }


    public function sendInvite(Request $request, $requirementId){
        session()->put("send_invite",$requirementId);
        return redirect($to="/user/dashboard?invite=true");
    }

    public function sendSelected(Request $request, $supplierId){
        $requirementId = session()->get("send_invite");

        SupplierRequirement::create(['user_id'=>$supplierId, 'requirement_id'=>$requirementId, 'status'=>config('constants.supplierRequirementStatus.pending')]);
        User::find($supplierId)->notify(new InviteReceived($requirementId));

        return redirect($to="/user/dashboard?invite=true")->withSuccess('Invite Sent Successfully');
    }


    public function updateRequirement(Request $request){

        $supplierId = auth()->user()->id;
        $requirementId = $request->rid;
        $items = $request->items;
        $sqItems = collect($items)->filter(function($item){return isset($item['quote_amount']) && $item['quote_amount']>0; })->map(function($item){
            unset($item['title']);
            unset($item['quantity']);
            unset($item['unit']);
            unset($item['created_at']);
            unset($item['updated_at']);
            unset($item['code']);
            unset($item['quote_quantity']);
            unset($item['requirement_id']);
            $item['requirement_item_id'] = $item['id'];
            $item['user_id'] = auth()->user()->id;
            $item['status'] = 0;
            return $item;
        });
        $sqItemsToDelete = collect($items)->filter(function($item){return !isset($item['quote_amount']) || (isset($item['quote_amount']) && $item['quote_amount']<=0); })->map(function($item){
            unset($item['title']);
            unset($item['quantity']);
            unset($item['unit']);
            unset($item['created_at']);
            unset($item['updated_at']);
            unset($item['code']);
            unset($item['quote_quantity']);
            unset($item['requirement_id']);
            $item['requirement_item_id'] = $item['id'];
            $item['user_id'] = auth()->user()->id;
            return $item;
        });
        //dd($sqItems);
        $msg = '';
        //Check if user quote exist
        $myReq = SupplierRequirement::with('squotes')->where(['requirement_id'=>$requirementId, 'user_id'=>$supplierId]);
        if($myReq->count()){
            //UPDATE QUOTE
            $msg = 'Quote Updated Successfully';
            session()->flash('success',$msg);
            Requirement::find($requirementId)->retailer->notify(new QuoteUpdated($requirementId, $supplierId));
            $myReq->update(['quote'=>$items, 'status'=>config('constants.supplierRequirementStatus.quoteSent')]);

            if(!$myReq->first()->squotes()->count()){
                $arr = $sqItems->toArray();


                $myReq->first()->squotes()->createMany($arr);
                // dd($arr);
            }
            else{
                $posted = collect($sqItems->pluck('id'));
                $existed = collect($myReq->first()->squotes->pluck('requirement_item_id'));
                $itemsToBeUpdated = $posted->intersect($existed);
                $itemsToBeRemoved = $existed->diff($posted);
                $itemsToBeCreated = $posted->diff($existed);


                if($itemsToBeRemoved->count()){
                    //dd(['itemsToBeRemoved-- -', $itemsToBeRemoved, $myReq->first()->squotes(), $existed, $posted]);
                    $itemsToBeRemoved = $itemsToBeRemoved->toArray();
                    $itemsToBeDeleted = collect($sqItemsToDelete)
                        ->filter(function($itm) use($itemsToBeRemoved) { return in_array($itm['requirement_item_id'],$itemsToBeRemoved); });

                        $itemsToBeDeleted->each(function($itm){ SupplierQuote::where(['requirement_item_id'=>$itm['requirement_item_id'], 'requirement_item_id'=>$itm['requirement_item_id']])->delete();});

                    //$records = $myReq->first()->squotes()->whereIn('requirement_id',$itemsToBeRemoved->toArray())->delete();
                    // dd([$itemsToBeDeleted , $sqItemsToDelete]);
                }
                if($itemsToBeCreated->count()){
                    $itemsToBeCreated = $itemsToBeCreated->toArray();
                    $itemsToBeCreatedC = collect($sqItems)
                        ->filter(function($itm) use($itemsToBeCreated) { return in_array($itm['requirement_item_id'],$itemsToBeCreated); })->map(function($item){ $item['status'] = 0; return $item;});
                        $myReq->first()->squotes()->createMany($itemsToBeCreatedC);
                    // dd(['itemsToBeCreated',$itemsToBeCreated, $itemsToBeCreatedC]);
                }

                if($itemsToBeUpdated->count()){
                    // dd('itemsToBeUpdated');
                    #ITEMS TO BE UPDATED

                    $squotes = $myReq->first()->squotes;
                    $idsToBeUpdated = $itemsToBeUpdated->toArray();
                    $itemsToBeSaved = collect($sqItems)
                        ->filter(function($itm) use($idsToBeUpdated) { return in_array($itm['requirement_item_id'],$idsToBeUpdated); });

                        $itemsToBeSaved->each(function($itm){
                             SupplierQuote::where(['requirement_item_id'=>$itm['requirement_item_id'], 'requirement_item_id'=>$itm['requirement_item_id'], 'status'=>0])->update(['quote_amount'=>$itm['quote_amount'], 'status'=>0]);
                             //FOR REVISED STATUS
                             SupplierQuote::where(['requirement_item_id'=>$itm['requirement_item_id'], 'requirement_item_id'=>$itm['requirement_item_id'], 'status'=>2])->update(['quote_amount'=>$itm['quote_amount'], 'status'=>3]);
                             SupplierQuote::where(['requirement_item_id'=>$itm['requirement_item_id'], 'requirement_item_id'=>$itm['requirement_item_id'], 'status'=>3])->update(['quote_amount'=>$itm['quote_amount'], 'status'=>3]);
                            });

                    // dd(['itemsToBeUpdated', $itemsToBeUpdated,  $itemsToBeSaved, $myReq->first(), $myReq->first()->squotes,  $existed, $posted]);

                }

                // dd([$itemsToBeUpdated, $itemsToBeRemoved, $itemsToBeCreated]);

                // dd($myReq->first()->squotes->pluck('id'));
            }
        }
        else{
            $msg = 'Quote Sent Successfully';
            session()->flash('success', $msg);
            Requirement::find($requirementId)->retailer->notify(new QuoteReceived($requirementId, $supplierId));
            $myReq = SupplierRequirement::create(
                ['requirement_id'=>$requirementId, 'user_id'=>$supplierId, 'quote'=>$items, 'status'=>config('constants.supplierRequirementStatus.quoteSent')]
            );

            $arr = $sqItems->toArray();
            $myReq->squotes()->createMany($arr);
        }
        // dd($sqItems);
        return response()->json(['type'=>'success', 'msg'=>$msg]);

    }

    public function getSingle(Request $request,$rid){
        $requirement = Requirement::where('id', $rid)->first();
        return response()->json($requirement);
    }

    public function approveQuote(Request $request){

        $srObj = SupplierRequirement::where('id', $request->qid);
        $srObj->update(['status'=>config('constants.supplierRequirementStatus.approved')]);
        User::find($srObj->first()->user_id)->notify(new QuoteApproved($srObj->first()->requirement_id, $srObj->first()->user_id));
        session()->flash('success','Quote Approved Successfully.');
        return response()->json([]);
    }

    public function close(Request $request, $rid){

        $srObj = Requirement::where('id', $rid);
        $srObj->update(['status'=>config('constants.requirements.closed')]);
        //User::find($srObj->first()->user_id)->notify(new QuoteApproved($srObj->first()->requirement_id, $srObj->first()->user_id));
        session()->flash('success','Requirement Closed Successfully.');
        return redirect()->back();
    }


    public function acceptItemQuote(Request $request, $sqid){

        $sqObj = SupplierQuote::where('id', $sqid);
        $sqObj->update(['status'=>config('constants.requirement_item.accepted')]);
        $sqData = $sqObj->first();
        $requirement_id = $sqData->supplier_requirement->requirement_id;
        $supplier_id = $sqData->user_id;
        User::find($supplier_id)->notify(new QuoteApproved($requirement_id, $supplier_id));
        return response()->json(['msg'=>'Item Quote Accepted Successfully','type'=>'success']);

    }

    public function rejectItemQuote(Request $request, $sqid){

        $sqObj = SupplierQuote::where('id', $sqid);
        $sqObj->update(['status'=>config('constants.requirement_item.rejected')]);
        $sqData = $sqObj->first();
        $requirement_id = $sqData->supplier_requirement->requirement_id;
        $supplier_id = $sqData->user_id;
        User::find($supplier_id)->notify(new QuoteRejected($requirement_id, $supplier_id));
        //User::find($srObj->first()->user_id)->notify(new QuoteApproved($srObj->first()->requirement_id, $srObj->first()->user_id));
        return response()->json(['msg'=>'Item Quote Rejected Successfully','type'=>'success']);

    }

    public function completeQuote(Request $request, $qid){


        $srObj = SupplierRequirement::where('id', $qid);

        $srObj->update(['status'=>config('constants.supplierRequirementStatus.completed')]);
        User::find($srObj->first()->user_id)->notify(new QuoteCOmpleted($srObj->first()->requirement_id, $srObj->first()->user_id));
        session()->flash('success','Quote Marked As Completed Successfully.');
        return redirect()->back();
    }

    public function rejectQuote(Request $request){

        $srObj = SupplierRequirement::where('id', $request->qid);
        $srObj->update(['status'=>config('constants.supplierRequirementStatus.rejected')]);
        User::find($srObj->first()->user_id)->notify(new QuoteRejected($srObj->first()->requirement_id, $srObj->first()->user_id));
        session()->flash('success','Quote Rejected Successfully.');
        return response()->json([]);
    }


    public function saveRating(Request $request){
        $rating = $request->rating;
        $feedback = $request->feedback;
        $retailer_id = auth()->user()->id;
        $supplier_id = $request->supplier_id;
        $requirement_id = $request->requirement_id;
        $rating_id = $request->rating_id;
        if(!$rating_id){
            UserRating::create([
                'retailer_id'   =>  $retailer_id,
                'feedback'   =>  $feedback,
                'rating'   =>  $rating,
                'supplier_id'   =>  $supplier_id,
                'requirement_id'   =>  $requirement_id,
            ]);
        }
        else{
            UserRating::where('id',$rating_id)->update([
                'retailer_id'   =>  $retailer_id,
                'feedback'   =>  $feedback,
                'rating'   =>  $rating,
                'supplier_id'   =>  $supplier_id,
                'requirement_id'   =>  $requirement_id,
            ]);
        }
        session()->flash('success','Rating Added Succeessfully.');
        return response()->json(['msg'=>'Rating Added Succeessfully.']);

    }


    public function showInvoice(Requirement $requirement){
        $settings = GeneralSetting::all();
        return view('site.requirement.invoice', compact('requirement','settings'));
    }


}

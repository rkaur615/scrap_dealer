<?php

namespace App\Http\Controllers;

use App\Models\ProductTitle;
use App\Models\UserCatalog;
use Illuminate\Http\Request;

class UserCatalogController extends Controller
{
    //
    public function list(Request $request){

        $products = UserCatalog::with('title', 'cat')->where('user_id', auth()->user()->id)->get();
        return view('site.catalog.list', compact('products'));
    }

    public function create(Request $request){
        $productDataArr = $request->all();
        $productData = [];
        $productData['category'] = $productDataArr['category'];
        $productData['price'] = $productDataArr['price'];
        $productData['quantity'] = $productDataArr['quantity'];
        $productData['title_id'] = $productDataArr['title'];
        $productData['unit'] = $productDataArr['unit'];
        $productData['user_id'] = auth()->user()->id;
        if($productDataArr['cid'] && $productDataArr['cid']>0){
            $catalogRes = UserCatalog::where('id',$productDataArr['cid']);
            $catalog = $catalogRes->first();
            $catalogRes->update($productData);
        }
        else{
            $catalog = UserCatalog::create($productData);
        }

        session()->flash('success','Catalog Updated Successfully');
        return response()->json(['type'=>'success', 'to'=>route('user.catalog.list')]);
        // return response()->json(['data'=>$catalog]);
    }

    public function add(Request $request){
        return view('site.catalog.add');
    }

    public function edit(Request $request, $catalogId){
        $this->authorize("viewCatalog", UserCatalog::find($catalogId));
        return view('site.catalog.add', compact('catalogId'));
    }

    public function update(Request $request){

    }

    public function delete(Request $request){


    }

    public function productTitles(Request $request){
        return response()->json(['data'=>ProductTitle::all()]);
    }



    public function productTitlesSelected(Request $request){

        $cats = $request->get('category');
        $selectedTitles = ProductTitle::whereIn('id', function($query)use($cats){ return $query->select("title_id")->from('user_catalogs')->whereIn('category', $cats);})->get();
        if(in_array('-1',$cats)){
            $pendingCats = ProductTitle::whereNotIn('id', function($query)use($cats){ return $query->select("title_id")->from('user_catalogs')->get(); })->get();
            $selectedTitles = $selectedTitles->merge($pendingCats);
        }
        return response()->json(['data'=>$selectedTitles]);
    }

    public function createProductTitle(Request $request){
        $data = $request->all();
        $productTitle = ProductTitle::create(['title'=>$data['title'], 'user_id'=>auth()->user()->id]);
        return response()->json($productTitle);
    }

    public function getSingle(Request $request,$catalogId){
        $catalog = UserCatalog::with('images','title','cat')->where(['id'=>$catalogId])->first();
        return response()->json($catalog);
    }
}

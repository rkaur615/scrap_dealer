<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateTicketCatRequest;
use App\Http\Requests\Admin\UpdateTicketCatRequest;
use App\Http\Resources\Admin\GetTicketCategoryResource;
use App\Models\TicketCategory;
use Illuminate\Http\Request;

class TicketCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ticketCats = TicketCategory::orderBy('id', 'desc')->paginate(10);
        return GetTicketCategoryResource::collection($ticketCats);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTicketCatRequest $request)
    {
        $ticketCats = TicketCategory::create($request->validated());
        if ($ticketCats) {
            return response()->json([
                'message'=>__('apiMessage.createCategory'),
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'error' => __('apiMessage.errorMsg'),
                'success' => false
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TicketCategory $ticketCategory)
    {
        return new GetTicketCategoryResource($ticketCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTicketCatRequest $request,TicketCategory $ticketCategory)
    {
        $ticketCategory->update($request->validated());
        return response()->json([
            'message' => __('apiMessage.categoryUpdateSuccess'),
            'success' => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TicketCategory $ticketCategory)
    {
        $ticketCategory->delete();
        return response()->json([
            'message'=>__('apiMessage.categoryDeleteSuccess'),
            'success' => true
        ], 200);
    }
}

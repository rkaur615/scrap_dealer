<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateTicketRequest;
use App\Http\Resources\Admin\GetTicketResource;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets = Ticket::with('category:id,name', 'user:id,name,phone_number,email')->orderBy('id', 'desc')->paginate(10);
        return GetTicketResource::collection($tickets);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTicketRequest $request)
    {
        $ticket = Ticket::create($request->validated());
        if ($ticket) {
            return response()->json([
                'message'=>__('apiMessage.createTicket'),
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
    public function show(Ticket $ticket)
    {
        return new GetTicketResource($ticket->load('category:id,name', 'user:id,name,phone_number,email'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateTicketRequest $request, $ticket)
    {
        $ticket->update($request->validated());
        return response()->json([
            'message' => __('apiMessage.updateTicket'),
            'success' => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateStatus(Request $request,Ticket $ticket)
    {
        $ticket->update(['status' => $request->status]);
        return response()->json([
            'message' => __('apiMessage.updateTicket'),
            'success' => true
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateTicketCommentRequest;
use App\Http\Requests\Admin\UpdateTicketCommentRequest;
use App\Http\Resources\Admin\GetTicketCommentResource;
use App\Models\TicketComment;
use Illuminate\Http\Request;

class TicketCommentController extends Controller
{

    public function getTicketComment(Request $request)
    {
        $comments = TicketComment::with('admin', 'user')
            ->where('ticket_id', $request->ticket_id)->get();
        return GetTicketCommentResource::collection($comments);
        }

    public function createAdminComment(CreateTicketCommentRequest $request)
    {
        $comment = TicketComment::create($request->validated() + ['admin_id' => auth()->id()]);
        if ($comment) {
            return response()->json([
                'message'=>__('apiMessage.postcomment'),
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'error' => __('apiMessage.errorMsg'),
                'success' => false
            ], 400);
        }
    }

    public function createUserComment(CreateTicketCommentRequest $request)
    {
        $comment = TicketComment::create($request->validated() + ['user_id' => auth()->id()]);
        if ($comment) {
            return response()->json([
                'message'=>__('apiMessage.postcomment'),
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'error' => __('apiMessage.errorMsg'),
                'success' => false
            ], 400);
        }
    }

    public function updateComment(UpdateTicketCommentRequest $request, TicketComment $comment)
    {
        $comment->update($request->validated());
        return response()->json([
            'message' => __('apiMessage.updatecomment'),
            'success' => true
        ]);
    }

}

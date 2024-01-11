<?php

namespace App\Http\Controllers;

use App\Models\Requirement;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    //
    public function index(Request $request, $uid, $rid){
        //Check if Room Exist
        $myId = (string) auth()->user()->id;
        // dd($myId);
        // $myId = 17;
        $data = [$uid, $myId];
        $room = null;
        $slug = (string) Str::uuid();
        // dump($data);
        // Room::where()
        $rooms = Room::all();
        //Find Matched Room
        $rooms = $rooms->filter(function($room) use($data){
            return !count(array_diff($room->users,$data));
        });
        // dd($rooms);
        // $rooms = Room::when($data, function($query) use ($data, $rid) {
        //     // $query->whereJsonContains('users',$data);
        //     $query->where(function ($query) use ($data) {
        //         foreach($data as $id) {
        //             $query->orWhereJsonContains('users', $id);
        //         }

        //     });
        //     //$query->where('requirement_id',$rid);
        // });
        if($rooms->count()){
            $room = $rooms->first();
        }
        else{
            //Create New Room
            $dataToSave = [
                'users' =>  $data,
                // 'requirement_id'    =>  $rid,
                'title' =>  $slug,
                'slug' =>  $slug,
            ];
            //            dd($dataToSave);
            $room = Room::create($dataToSave);

        }

        return redirect()->route('chat.slug', [$room->slug]);
    }

    public function chatSlug(Request $request, Room $room){
        //Check if Room Exist
        $myId = (string) auth()->user()->id;
        $orooms = Room::when($myId, function($query) use ($myId) {
            // $query->whereJsonContains('users',$data);
            $query->orWhereJsonContains('users', $myId);
            // $query->where('requirement_id',$rid);
        })->get();
        //->where('requirement_id','>',0)


        // dd($orooms);
        // $room = null;


        return view('chat.room',['room'=>$room, 'orooms'=>$orooms]);
    }
}

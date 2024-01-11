<?php

namespace App\Http\Livewire\Chat;

use App\Models\Message;
use Livewire\Component;

class Messages extends Component
{
    public $messages;
    public $room;
    protected $listeners = ['messages.refresh'=>'refresh'];
    public function mount($room, $messages){
        $this->messages = $messages;
        $this->room = $room;
    }

    public function refresh($id=null){
        $this->messages = Message::where('room_id',$this->room->id)->orderBy('id','ASC')->get();
        // dd($this->messages);
    }

    public function render()
    {
        return view('livewire.chat.messages');
    }
}

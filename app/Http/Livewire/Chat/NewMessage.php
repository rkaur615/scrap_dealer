<?php

namespace App\Http\Livewire\Chat;

use App\Models\Room;
use Livewire\Component;

class NewMessage extends Component
{
    public $body = '';
    public $room;
    public function mount(Room $room){
        $this->room = $room;
    }

    public function send(){
        
        $uid = auth()->user()->id;
        $msg = $this->room->messages()->create([
            'body'  =>  $this->body,
            'user_id'  =>  $uid,
        ]);
        $this->body = '';
        $this->emit('messages.refresh',$this->room->id);
    }

    public function render()
    {
        return view('livewire.chat.new-message');
    }
}

<div wire:poll.2s="refresh" id="scroller">
@foreach ($messages as $message)

  @if ($message->user_id===auth()->user()->id)
  <div class="row no-gutters">
    <div class="col-md-3 offset-md-9">
      <div class="chat-bubble chat-bubble--right">
        {{-- @dd($messages->count()) --}}
        {{$message->body}}
      </div>
    </div>
  </div>
  @else
  <div class="row no-gutters">
    <div class="col-md-12">
      <div class="chat-bubble chat-bubble--left float-start">
        {{$message->body}}
      </div>
    </div>
  </div>
  @endif


@endforeach
<script>
window.setInterval(function() {
  var elem = document.getElementsByClassName('chatAreaPanel')[0];
  elem.scrollTop = elem.scrollHeight;
}, 2000);
</script>
</div>

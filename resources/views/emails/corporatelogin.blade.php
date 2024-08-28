<div>
            
            fullName : {{$data["fullName"]}}<br>
            phone : {{$data["phone"]}}<br>
            branch : {{$data["branch"]}}<br>
            complaint : {{$data["complaint"]}}<br>
            upload :     @if (isset($image))
      
        <img src="{{ $message->embed($image) }}" alt="Uploaded Image">
    @else
        <p>No image uploaded</p>
    @endif
</div>
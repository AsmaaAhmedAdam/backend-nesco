<div>
            
            fullName : {{$data["fullName"]}}<br>
            email : {{$data["email"]}}<br>
            dateOfBirth : {{$data["dateOfBirth"]}}<br>
            gender : {{$data["gender"]}}<br>
            maritalStatus : {{$data["maritalStatus"]}}<br>
            nationality : {{$data["nationality"]}}<br>
            group : {{$data["group"]}}<br>
            group : {{$data["group"]}}<br>
            district : {{$data["district"]}}<br>
            mobile : {{$data["mobile"]}}<br>
            school : {{$data["school"]}}<br>
            schoolqualification : {{$data["schoolqualification"]}}<br>
            Job : {{$data["Job"]}}<br>
              upload :     @if (isset($image))
      
            <img src="{{ $message->embed($image) }}" alt="Uploaded Image">
               @else
                     <p>No image uploaded</p>
                     @endif
</div>
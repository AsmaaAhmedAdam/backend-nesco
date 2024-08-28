<div>
            
            fullName : {{$data["fullName"]}}<br>
            IDNumber : {{$data["IDNumber"]}}<br>
            nationality : {{$data["nationality"]}}<br>
            dateOfBirth : {{$data["dateOfBirth"]}}<br>
            educQualification : {{$data["educQualification"]}}<br>
            mobileNumber : {{$data["mobileNumber"]}}<br>
            email : {{$data["email"]}}<br>
            phoneNumber : {{$data["phoneNumber"]}}<br>
            address : {{$data["address"]}}<br>


            annualIncome : {{$data["annualIncome"]}}<br>
            bankAccountType : {{$data["bankAccountType"]}}<br>
            commercialActivity : {{$data["commercialActivity"]}}<br>
            expectedCapital : {{$data["expectedCapital"]}}<br>
            lown : {{$data["lown"]}}<br>
            manageBusiness : {{$data["manageBusiness"]}}<br>
            havepartners : {{$data["havepartners"]}}<br>


            jobTitle : {{$data["jobTitle"]}}<br>
            employer : {{$data["employer"]}}<br>
            workAddress : {{$data["workAddress"]}}<br>
            joiningDate : {{$data["joiningDate"]}}<br>
            experience : {{$data["experience"]}}<br>
            companyEmail : {{$data["companyEmail"]}}<br>
            previousJobs : {{$data["previousJobs"]}}<br>



            franchiseReason : {{$data["franchiseReason"]}}<br>
            branchesToStart : {{$data["branchesToStart"]}}<br>
            expandPlans : {{$data["expandPlans"]}}<br>
            franchiseCity : {{$data["franchiseCity"]}}<br>
            suggestedLocation : {{$data["suggestedLocation"]}}<br>
            suggestions : {{$data["suggestions"]}}<br>
            
             uploadFile :     @if (isset($image))
      
        <img src="{{ $message->embed($image) }}" alt="Uploaded Image">
    @else
        <p>No image uploaded</p>
    @endif
           
</div>

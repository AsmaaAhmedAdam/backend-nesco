<?php




if (! function_exists('Gender')) {

    function Gender() {

        $type = [
            '0' => 'ولد',
            '1' => 'بنت'

        ];

        return $type;
    }
}


if (! function_exists('Status')) {

    function Status() {

        $status = [
            '0' => 'غير مفعل',
            '1' => 'مفعل'

        ];

        return $status;
    }
}

if (! function_exists('YesOrNo')) {

    function YesOrNo() {

        $status = [
            '1' => 'نعم',
            '2' => 'لا'

        ];

        return $status;
    }
}



if (! function_exists('add3dots')) {

    function add3dots($string, $repl, $limit)
    {
      if(strlen($string) > $limit)
      {
        return substr($string, 0, $limit) . $repl;
      }
      else
      {
        return $string;
      }
    }

}


if (! function_exists('GetMobileNumber')) {

    function GetMobileNumber($mobile)
    {

        $mobile = ltrim($mobile,'+');

        $arr = explode('971',$mobile);

        if(count($arr) == 2) {
            $mobile = $arr[1];
        }

        return $mobile;

    }

}






if (! function_exists('Push_Notification')) {

    function Push_Notification($users_device_token,$title,$body) {


        $SERVER_API_KEY = env('FCM_SERVER_KEY');

        $data = [
            "registration_ids" => $users_device_token,
            "notification" => [
                "title" => $title,
                "body" => $body,
                "sound" => 'default',
            ]
        ];

        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

    }

}




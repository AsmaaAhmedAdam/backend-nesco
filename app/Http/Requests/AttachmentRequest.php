<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AttachmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $tables = DB::select('SHOW TABLES');
        $res = [] ;
        foreach($tables as $table)
        {
            $res[]=current((Array)$table);
        }
        $res = implode(",",$res) ;

        $fileValidation ='image|mimes:jpeg,png,jpg,gif' ;
        if($this->attachment_type == 'image'){

           $fileValidation ='image|mimes:jpeg,png,jpg,gif' ;
       }
       elseif($this->attachment_type == 'file'){
        $fileValidation = 'mimes:pdf';

       }elseif($this->attachment_type == 'video'){
        $fileValidation = 'mimes:mp4,mov,ogg,qt|max:20000'; // Max 20MB

       }
        return [
            'file' => 'nullable|'. $fileValidation,
            'files' => 'nullable|required_if:file,NULL|array',
            'files.*' => 'nullable|file',
            'attachment_type' => 'required|in:image,file,audio,video',
            'model' => 'required|in:'. $res,
        ];
    }
    
       protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'code' =>403,
            'message' => implode(', ', $validator->errors()->all()),
        ], 500));
    }
}

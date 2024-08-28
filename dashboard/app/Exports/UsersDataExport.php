<?php

namespace App\Exports;


use App\Models\User;
use Illuminate\Contracts\view\view;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UsersDataExport implements FromCollection ,WithHeadings ,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    
    //FromView, ShouldAutoSize
        use Exportable;
        private $users;
        

        public function __construct()
        {
            $this->users = User::all();
        }
        
        // public function view() : view
        // {
        //     return view('admin.users.index' , [
        //         $this->users
        //     ]);
       


        // }
        public function collection()
        {
            return User::select('*')->get();
        }

         public function view() : view
        {
            return view('admin.users.index' , [
                $this->users
            ]);
    
}
        
        public function map($users):array
        {
            return [
                $users->id,
                $users->name,
                $users->email,
                $users->mobile,
                $users->role,
                $users->provider_type,
                $users->status,
                // $users->created_at->format('d-m-Y'),
            ];
        }

        public function headings():array
        {
            return [
                '#',
                'Name',
                'Email',
                'Mobile',
                'Role',
                'provider_type',
                'Status',
                // 'Created At',
            ];
        }
}
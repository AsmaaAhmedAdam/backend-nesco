


@php
	$lang = session()->get('admin_lang');
    if($lang == null) {
        $lang = 'ar';
    }
	app()->setLocale($lang);

    $setting = App\Models\Setting::first();
@endphp




@extends('admin.layouts.master')

@section('top_title') {{ trans('home.users') }} @endsection


@section('main_title') {{ trans('home.users') }}   @endsection



@section('sub_title')

    <a href="{{ url('admin_panel/users/create') }}" class="btn btn-light-warning font-weight-bolder btn-sm">
        {{ trans('home.add_user') }}
    </a>
    


@endsection


@section('header')
    <style>
        td,th { text-align: center }
    </style>

@endsection

@section('content')

    @include('flash-message')




    <!--begin::Card-->
 
   
    
    <div class="card card-custom">

        <div class="card-body">
       

            <!--begin: Datatable-->
            <table class="table table-bordered table-checkable" id="m_table_1">
            <div>
            <form action="{{route('users.download-excel')}}" method="POST" target="__blank">
                @csrf
                <div>
            <button type="submit"
            class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">

            <div class="flex justify-b">
                
                <svg:svg xmlns:svg="http://www.w3.org/2000/svg">
        <svg:circle cx='50' cy='25' r='20'/>
     </svg:svg>

            </div>
            <div>

                Export Excel
    
            </div>
         </div>
          </button>
        </div>

            </form>
         </div>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>  {{ trans('home.name') }}  </th>
                        <th>  {{ trans('home.email') }}  </th>
                        <th>  {{ trans('home.mobile') }}  </th>
                        <th>  {{ trans('home.provider_type') }}  </th>
                        <th> {{ trans('home.tools') }}  </th>
                       
                    </tr>
                </thead>
                <tbody>

                    @php $x = 1; @endphp


                    @foreach($Item as $member)

                        <tr>
                            <td> {{ $x }} </td>
                            <td> {{ $member->name }} </td>
                            <td> {{ $member->email }} </td>
                            <td> {{ $member->mobile }} </td>
                            <td> {{ $member->provider_type }} </td>
                           

                            <td nowrap>
                                <a href="{{ url('admin_panel/users/'). '/' . $member->id . '/edit'}}" class='m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill' title='{{trans('home.edit')}}'>
                                    <i class="la la-edit"></i>
                                </a>

                                <span class='DeletingModal m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill' name="{{ $member->id }}" title='{{trans('home.delete')}}'>
                                        <i class="fa fa-trash"></i>
                                </span>
                            </td>




                        </tr>

                        @php $x = $x + 1; @endphp
                    @endforeach


                </tbody>
            </table>
            <!--end: Datatable-->

        </div>
    </div>
    <!--end::Card-->


@endsection



@section('footer')

      <script>

        $(document).ready(function () {

            $("#m_table_1").DataTable({
                responsive: !0,
                paging: !0,
                columnDefs: [ {
                    targets: -1,
                    title: "{{ trans('home.tools') }} ",
                    orderable: !1
                    }
                ]
            });


            $('#m_table_1').on('click', '.DeletingModal', function () {

                var ID = $(this).attr("name");


                swal({
                        title: "{{ trans('home.delete_msg1') }}",
                        text: "{{ trans('home.delete_msg2') }}",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "{{ trans('home.yes') }}",
                        cancelButtonText: "{{ trans('home.no') }}",
                        closeOnConfirm: true,
                        closeOnCancel: true
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            window.location.href = '{{ url('admin_panel/users/destroy') }}' + '/' + ID;

                        }
                    });
            });

        });

    </script>


@endsection















@extends('admin.layouts.master')

@section('top_title') {{ trans('home.faq') }} @endsection

@section('main_title') {{ trans('home.faq') }}  @endsection

@php
	$lang = session()->get('admin_lang');
    if($lang == null) {
        $lang = 'ar';
    }
	app()->setLocale($lang);
@endphp

@section('sub_title')

    <a href="{{ url('admin_panel/faq/create') }}" class="btn btn-light-warning font-weight-bolder btn-sm">
        {{ trans('home.add-faq') }}
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
                <thead>
                    <tr>
                        <th>#</th>
                        <th>  {{ trans('home.ques') }}  </th>
                        <th> {{ trans('home.tools') }} </th>
                    </tr>
                </thead>
                <tbody>

                    @php $x = 1; @endphp

                    @foreach($Item as $value)

                    <tr>
                        <td> {{ $x }} </td>
                        <td> {{ $value->{$lang.'_title'} }} </td>

                        <td>
                            <a href="{{ url('admin_panel/faq/'). '/' . $value->id . '/edit'}}" class='m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill' title='Edit'>
                                <i class="la la-edit"></i>
                            </a>
                            <span class='DeletingModal m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill' name="{{ $value->id }}" title='Delete'>
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
                columnDefs: [
                    {
                        targets: -1,
                        title: "{{ trans('home.tools') }}",
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
                        window.location.href = '{{ url('admin_panel/faq/destroy') }}' + '/' + ID;

                    }
                });

            });




        });

    </script>


@endsection









@php
	$lang = session()->get('admin_lang');
    if($lang == null) {
        $lang = 'ar';
    }
	app()->setLocale($lang);
@endphp

@extends('admin.layouts.master')

@section('top_title') {{ trans('home.coupon') }} @endsection

@section('main_title') {{ trans('home.add-coupon') }}  @endsection

@section('sub_title')

    <a href="{{ url('admin_panel/coupon/create') }}" class="btn btn-light-warning font-weight-bolder btn-sm">
        {{ trans('home.add-coupon') }}
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
                       <th> {{ trans('home.coupon') }} </th>
                       <th> {{ trans('home.value') }} </th>
                       <th> {{ trans('home.status') }} </th>
                       <th> {{ trans('home.orders') }} </th>
                       <th> {{ trans('home.tools') }} </th>
                    </tr>
                </thead>

                <tbody>

                    @php $x = 1; @endphp

                    @foreach($Item as $value)

                        <tr>
                            <td> {{ $x }} </td>
                            <td>
                                {{ $value->title }}
                            </td>
                            <td>
                                {{ $value->value }} {{ $value->value_type == 'percentage' ? '%' : trans('home.aed') }}
                            </td>
                            <td>
                                {{ $value->status == 0 ? trans('home.un-active') : trans('home.active') }}
                            </td>


                            <td>
                                @if($value->orders()->count() != 0)
                                <a href="{{ url('admin_panel/coupon/orders'). '/' . $value->id }}" style="color:#fff; background:blue;width:25px;height:25px;line-height:25px;border-radius:50%;text-align:center;font-weight:bold;display:inline-block">
                                    {{ $value->orders()->count() }}
                                </a>
                                @else
                                <span style="color:#fff; background:blue;width:25px;height:25px;line-height:25px;border-radius:50%;text-align:center;font-weight:bold;display:inline-block">
                                    {{ $value->orders()->count() }}
                                </span>
                                @endif
                            </td>

                            <td nowrap>

                                <a href="{{ url('admin_panel/coupon/'). '/' . $value->id . '/edit'}}" class='m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill' title='Edit'>
                                    <i class="la la-edit"></i>
                                </a>

                                @if($value->status == '0')
                                <span class="accept" data-id="{{ $value->id }}" style="color: green;font-weight:bold;cursor:pointer;text-align: center;">
                                    <i class="fa fa-thumbs-up" style="font-size: 20px" aria-hidden="true"></i>
                                </span>

                                @else
                                <span class="refused" data-id="{{ $value->id }}" style="color: red;font-weight:bold;cursor:pointer;text-align: center;">
                                    <i class="fa fa-thumbs-down" style="font-size: 20px" aria-hidden="true"></i>
                                </span>
                                @endif

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


            $('#m_table_1').on('click', '.accept', function () {

                var ID = $(this).data("id");


                swal({
                      title: "{{ trans('home.coupon_msg1') }}",
                      text: "{{ trans('home.coupon_msg2') }}",
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
                            window.location.href = '{{ url('admin_panel/coupon_accept') }}' + '/' + ID;

                        }
                    });
            });


            $('#m_table_1').on('click', '.refused', function () {

                var ID = $(this).data("id");


                    swal({
                      title: "{{ trans('home.coupon_msg3') }}",
                      text: "{{ trans('home.coupon_msg4') }}",
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
                            window.location.href = '{{ url('admin_panel/coupon_refused') }}' + '/' + ID;

                        }
                    });
            });


        });

    </script>


@endsection









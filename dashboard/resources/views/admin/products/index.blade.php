@php
	$lang = session()->get('admin_lang');
    if($lang == null) {
        $lang = 'ar';
    }
	app()->setLocale($lang);
@endphp

@extends('admin.layouts.master')


@section('main_title') {{ trans('home.products') }} @endsection

@section('top_title') {{ trans('home.products') }} @endsection

@section('sub_title')

    <a href="{{ url('admin_panel/products/create') }}" class="btn btn-light-warning font-weight-bolder btn-sm">
        {{ trans('home.add_product') }}
    </a>

@endsection

@section('header')
    <style>

        td,th { text-align: center }

        .is_popularity i {
            color: blue
        }

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
                        <th>  {{ trans('home.pic') }} </th>
                        <th>  {{ trans('home.cat_name') }} </th>
                        <th>   {{ trans('home.name') }} </th>
                        <th> {{ trans('home.tools') }} </th>
                    </tr>
                </thead>
                <tbody>

                    @php $x = 1; @endphp

                    @foreach($Item as $value)

                    <tr>
                        <td> {{ $x }} </td>

                        <td>
                            <img src="{{ $value->pic }}?{{rand()}}" style="height:150px;width:150px;display: block;margin: auto;">
                        </td>

                        <td>
                            @if($value->category)
                                <a href="{{ url("admin_panel/product_categories/".$value->category->id."/edit") }}">
                                    {{ $value->category->{$lang.'_title'} }}
                                </a>
                            @endif
                        </td>

                        <td> {{ $value->{$lang.'_title'} }} </td>

                        <td>

                            <a href="{{ url('admin_panel/products/popularity'). '/' . $value->id }}" class='{{ $value->popularity == 1 ? 'is_popularity' : '' }}' title=''>
                                <i class="fa fa-star"></i>
                            </a>

                            <a href="{{ url('admin_panel/products/'). '/' . $value->id . '/edit'}}" class='m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill' title='Edit'>
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
                        title: "{{trans('home.tools')}}",
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
                            window.location.href = '{{ url('admin_panel/products/destroy') }}' + '/' + ID;

                        }
                    });
            });


        });

    </script>


@endsection









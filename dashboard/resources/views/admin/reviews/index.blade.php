@php
	$lang = session()->get('admin_lang');
    if($lang == null) {
        $lang = 'ar';
    }
	app()->setLocale($lang);
@endphp

@extends('admin.layouts.master')

@section('top_title') {{ trans('home.reviews') }} @endsection

@section('main_title') {{ trans('home.reviews') }}  @endsection


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
                        <th> {{ trans('home.product') }} </th>
                        <th> {{ trans('home.user') }}   </th>
                        <th> {{ trans('home.value') }}   </th>
                        <th> {{ trans('home.status') }}   </th>
                        <th> {{ trans('home.notes') }}  </th>
                        <th> {{ trans('home.tools') }} </th>
                    </tr>
                </thead>
                <tbody>

                    @php $x = 1; @endphp

                    @foreach($Item as $value)

                        <tr>
                            <td> {{ $x }} </td>

                            <td> {{ $value->product != null ? $value->product->{$lang.'_title'} : '' }} </td>

                            <td> {{ $value->user != null ? $value->user->name : ''  }} </td>

                            <td> {{ $value->value  }} </td>

                            <td> {{ trans('home.'.$value->status) }} </td>

                            <td>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal{{$value->id}}">
                                    {{ trans('home.view_note') }}
                                </button>
                            </td>

                            <td>
                                @if($value->status == 'hold')
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

                        <div class="modal fade" id="exampleModal{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title">
                                    {{ trans('home.note') }}
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        {{$value->notes}}
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                        {{ trans('home.close') }}
                                    </button>
                                </div>
                            </div>
                            </div>
                        </div>

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
                            window.location.href = '{{ url('admin_panel/messages/destroy') }}' + '/' + ID;

                        }
                    });
            });


            $('#m_table_1').on('click', '.accept', function () {

                var ID = $(this).data("id");


                swal({
                      title: "{{ trans('home.review_msg1') }}",
                      text: "{{ trans('home.review_msg2') }}",
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
                            window.location.href = '{{ url('admin_panel/review_accept') }}' + '/' + ID;

                        }
                    });
            });


            $('#m_table_1').on('click', '.refused', function () {

                var ID = $(this).data("id");


                    swal({
                      title: "{{ trans('home.review_msg3') }}",
                      text: "{{ trans('home.review_msg4') }}",
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
                            window.location.href = '{{ url('admin_panel/review_refused') }}' + '/' + ID;

                        }
                    });
            });

        });

    </script>


@endsection









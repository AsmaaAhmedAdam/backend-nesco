@php
	$lang = session()->get('admin_lang');
    if($lang == null) {
        $lang = 'ar';
    }
	app()->setLocale($lang);
@endphp

@extends('admin.layouts.master')

@section('top_title') {{ trans('home.edit_user') }}  @endsection

@section('main_title')  {{ trans('home.edit_user') }}   @endsection


@section('header')
    <style>
        .card-body .col-sm-12 , .card-body .col-sm-6 { margin-bottom: 20px }

        th,td { text-align: center !important;vertical-align:middle !important }

    </style>
    
    <style>

       td,th { text-align: center }

       .fa , .btn.m-btn--hover-brand:not(.btn-secondary):not(.btn-outline-light) i  {
         color: #898b96;
         cursor: pointer;
         display: inline-block;
         margin-left: 5px;
         margin-right: 5px;
         font-size: 25px;
       }

       .btn.m-btn--hover-brand:not(.btn-secondary):not(.btn-outline-light) {
           margin-top: -10px
        }

        .table th, .table td {
            padding: 0.75rem;
            vertical-align: middle;
            border-top: 1px solid #EBEDF3;
        }
        td, th {
            text-align: center;
        }

    </style>
@endsection


@section('content')

@include('flash-message')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Start Row -->
<div class="row">

   <div class="col-md-12">

      <!--begin::Card-->
      <div class="card card-custom">

         <div class="card-header">
            <h3 class="card-title">
                {{ trans('home.edit_user') }}
            </h3>
         </div>

         <div class="card-header card-header-tabs-line">
            <div class="card-toolbar">
                <ul class="nav nav-tabs nav-bold nav-tabs-line">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#kt_tab_pane_1_4">
                            <span class="nav-icon">
                                <i class="flaticon2-chat-1"></i>
                            </span>
                            <span class="nav-text">
                                {{ trans('home.edit_user') }}
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#kt_tab_pane_2_4">
                            <span class="nav-icon">
                                <i class="flaticon2-drop"></i>
                            </span>
                            <span class="nav-text">
                                {{ trans('home.invoices') }}
                            </span>
                        </a>
                    </li>

                </ul>
            </div>

        </div>
        <div class="card-body">
            <div class="tab-content">

                <div class="tab-pane fade show active" id="kt_tab_pane_1_4" role="tabpanel" aria-labelledby="kt_tab_pane_1_4">
                    @include('admin.users.edit_form')
                </div>
                
                                            
                @php $invoices = App\Models\Invoice::where('user_id',$Item->id)->where('status','done')->get() @endphp


                <div class="tab-pane fade" id="kt_tab_pane_2_4" role="tabpanel" aria-labelledby="kt_tab_pane_2_4">
                    
                    <table class="table table-bordered table-checkable" id="m_table_1">
                      <thead>
                         <tr>
                            <th> # </th>
                            <th> {{ trans('home.date') }}   </th>
                            <th> {{ trans('home.count') }}  </th>
                            <th> {{ trans('home.sub_total') }} </th>
                            <th> {{ trans('home.shipping_value') }} </th>
                            <th> {{ trans('home.coupon_value') }} </th>
                            <th> {{ trans('home.total') }} </th>
                            <th> {{ trans('home.status') }} </th>
                            <th> {{ trans('home.tools') }} </th>
                         </tr>
                      </thead>
                      <tbody>
                
                         @php $x = 1; @endphp
                
                         @foreach($invoices as $value)
                
                
                            <tr>
                               <td>
                                  <a href="{{ url('admin_panel/invoice_details/'.$value->id) }}" style="color:blue;font-weight:bold">
                                     {{ $value->serial_number }}
                                  </a>
                                </td>
                
                                
                
                                <td>
                                   {{ Carbon\Carbon::parse($value->created_at)->format('Y-m-d H:i') }}
                                </td>
                
                                <td>
                                  {{ $value->count_items }}
                                </td>
                
                                <td>
                                   {{ $value->sub_total }} {{ trans('home.aed') }}
                                </td>
                
                                <td>
                                    {{ $value->shipping_value }} {{ trans('home.aed') }}
                                 </td>
                
                                 <td>
                                    {{ $value->coupon_value }} {{ trans('home.aed') }}
                                 </td>
                
                                 <td>
                                    {{ $value->total }} {{ trans('home.aed') }}
                                 </td>
                
                                 <td>
                                    {{ trans('home.'.$value->status2) }}
                                 </td>
                
                                 <td nowrap>
                
                                    <a href="{{ url('admin_panel/invoice_details/'.$value->id) }}" style="color:blue;font-weight:bold">
                                       <i class="fa fa-eye"></i>
                                    </a>
                
                                    @if($value->status2 != 'in progress')
                                       <i class="fa fa-tasks invoice_status" title="in progress" data-id="{{ $value->id }}" data-type="in progress" aria-hidden="true"></i>
                                    @else
                                       <i class="fa fa-tasks" title="in progress" style="color: green" aria-hidden="true"></i>
                                    @endif
                
                                    @if($value->status2 != 'delivered')
                                       <i class="fa fa-thumbs-up invoice_status" title="delivered"  data-id="{{ $value->id }}" data-type="delivered"  aria-hidden="true"></i>
                                    @else
                                       <i class="fa fa-thumbs-up" title="delivered"  style="color: green"  aria-hidden="true"></i>
                                    @endif
                
                                    @if($value->status2 == 'cancelled')
                                       <i class="fa fa-thumbs-down" title="cancelled" style="color: green"  aria-hidden="true"></i>
                                    @else
                                       <i class="fa fa-thumbs-down invoice_status" title="cancelled" data-id="{{ $value->id }}" data-type="cancelled"  aria-hidden="true"></i>
                                    @endif
                
                
                                    <a href="{{ url("admin_panel/invoices/print/".$value->id) }}">
                                       <i class="fa fa-print" aria-hidden="true"></i>
                                    </a>
                
                
                                    <span class='DeletingModal m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill' name="{{ $value->id }}" title='Delete'>
                                          <i class="fa fa-trash"></i>
                                    </span>
                                 </td>
                
                            </tr>
                
                         @endforeach
                
                
                
                
                      </tbody>
                   </table>


                </div>

            </div>
        </div>







      </div>
      <!--end::Card-->

   </div>



</div>
<!-- End Row -->

@endsection



@Section('footer')

    <script>

        $(document).ready(function () {
    
            $("#m_table_1").DataTable({
                responsive: !0,
                paging: !0,
                "bSort": false
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
                        window.location.href = '{{ url('admin_panel/invoices/destroy') }}' + '/' + ID;
    
                    }
                });
            });
    
    
            $(".invoice_status").click(function (e) {
    
                var element = $(this);
    
                $.ajax({
                    url: '{{ url('admin_panel/invoice_status') }}',
                    method: "get",
                    data: {
                        _token: '{{ csrf_token() }}',
                        invoice_id: element.attr("data-id"),
                        invoice_status: element.attr("data-type")
                    },
                    dataType: "json",
                    success: function (response) {
    
                        if(response.msg != null) {
                            swal({
                                title: "",
                                text: response.msg,
                                imageUrl: '{{ asset('img/sent.jpg') }}'
                            });
                        }
    
                        $("#datatable_content").html(response.data);
    
                        setTimeout(function(){location.reload(true); }, 2000);
    
                    }
                });
    
            });
    
    
        });
    
    
    </script>

@endsection

@php
	$lang = session()->get('admin_lang');
    if($lang == null) {
        $lang = 'ar';
    }
	app()->setLocale($lang);
@endphp

@extends('admin.layouts.master')

@section('top_title') {{trans('home.print_invoice')}}  @endsection

@section('main_title') {{trans('home.print_invoice')}}  @endsection


@section('header')

    <style>

       th,td { text-align: center !important }

       div.dataTables_wrapper div.dataTables_info {
           display: none
       }

       .m-radio { display: inline-block !important }

       .m-radio>span, .m-checkbox>span { top: 6px }

       .card-body {
            padding-top: 0
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 1px solid #2ea4c2;
            color: #1d7893;
        }

        .table-bordered {
            border: 1px solid #2ea4c2;
        }

        .table-bordered th, .table-bordered td {
            border: 1px solid #2ea4c2;
        }


       tr:nth-child(even) {
        background-color: #d3eef5;
       }




       @media print {


            #print_page , .footer , header ,  #kt_header , #kt_subheader , #kt_aside , #kt_header_mobile  {
                display: none !important
            }

           .print_img {
               display: block !important
           }


       }


    </style>


@endsection



@php
   $setting = App\Models\Setting::first();
@endphp


@section('content')


<div class="col-md-12">


    @include('flash-message')

   <!--begin::Card-->
   <div id="DivIdToPrint" class="card card-custom gutter-b example example-compact">


      <div class="card-body">


        <div class="row" style="padding: 20px;padding-left: 50px;padding-right:50px">
            <div class="col-sm-12">
                <img class="print_img" src="{{ url('logo.jpeg') }}" style="height: 100px;display: block;margin: auto;">

                <h1 style="text-align: center;margin-top: 20px;font-size: 35px;font-weight: bold;color: red;"> {{ $invoice->serial_number }} </h1>
            </div>
        </div>




{{-- 

        <div class="table-responsive" style="margin-bottom:30px">
            <div class="m-section__content">

                <!--begin: Datatable -->
                <table class="table table-striped- table-bordered table-hover table-checkable">
                    <thead>
                        <tr>
                            <th> {{ trans('home.name') }}  </th>
                            <th> {{ $shipping_info->name  }} </th>
                            <th> {{ Carbon\Carbon::parse($shipping_info->created_at)->format('Y-m-d') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th style="color: #1d7893">
                                {{ trans('home.address') }}
                            </th>
                            <th style="color: #1d7893">
                                @if($shipping_info->city != null)
                                     {{ $shipping_info->city->{$lang.'_name'} }}
                                @endif

                                @if($shipping_info->address != null)
                                - {{$shipping_info->address}}
                                @endif
                            </th>
                            <th style="color: #1d7893">
                                {{ $shipping_info->mobile }}
                            </th>
                        </tr>
                    </tbody>

                </table>

            </div>
        </div> --}}






        <div class="table-responsive">
            <div class="m-section__content">

                <!--begin: Datatable -->
                <table class="table table-striped- table-bordered table-hover table-checkable" id="m_table_1">
                    <thead>
                        <tr>
                            <th> {{ trans('home.product') }} </th>
                            <th> {{ trans('home.quantity') }}  </th>
                            <th> {{ trans('home.price') }} </th>
                            <th> {{ trans('home.total') }} </th>
                        </tr>
                    </thead>
                    <tbody>

                     @php $x = 1; @endphp
                     @foreach($details as $value)
                     


                     <tr>

                        <td>
                           <a style="color:blue;font-weight:bold">
                               
                              

                              
                            </a>
                        </td>

                        <td>
                           <b>
                              {{ $value->quantity }}
                           </b>
                        </td>

                        <td> {{ $value->price }} {{ trans('home.aed') }} </td>

                        <td> {{ $value->total }} {{ trans('home.aed') }} </td>

                     </tr>

                     @endforeach

                     <tr>
                        <th style="font-weight: bold;font-size:15px"> {{ trans('home.sub_total') }} </th>
                        <td colspan="5"> {{ $invoice->total }} {{ trans('home.aed') }} </td>
                     </tr>

                      <tr>
                        <th style="font-weight: bold;font-size:15px"> {{ trans('home.shipping_value') }} </th>
                        <td colspan="5"> {{ $invoice->shipping_value }} {{ trans('home.aed') }} </td>
                     </tr>
                     <tr>
                        <th style="font-weight: bold;font-size:15px"> {{ trans('home.coupon_value') }}  </th>
                        <td colspan="5">
                           {{ $invoice->coupon != null ? $invoice->coupon->title : '' }} ({{ $invoice->coupon_value }} ) {{ trans('home.aed') }}

                        </td>
                     </tr>

                     <tr>
                        @php
                        $discount = $percent = null;
                        $total = $invoice->total + ($invoice->tax  / 100) * ($invoice->total) + $invoice->shipping_value;
                        if(!empty($invoice->coupon)) {
                           $discount = $invoice->coupon->value;
                           $total  = ($invoice->coupon->value_type == 'value') ? ($total - $discount) : $total - (($discount / 100) * $total);
                        }
                        @endphp
                        <th style="font-weight: bold;font-size:15px"> {{ trans('home.total') }} </th>
                        <td colspan="5"> {{ $total }} {{ trans('home.aed') }} </td>
                     </tr>


                    </tbody>
                </table>

            </div>
        </div>


      </div>




   </div>
   <!--end::Card-->

   <button type="button" class="btn btn-primary" id="print_page" style="margin-top: 20px">
       {{ trans('home.print') }}
    </button>


</div>

@endsection



@section('footer')

    <script>

        $(document).ready(function () {

            $('#print_page').click(function() {


                window.print();
                return false;

            });





        });



    </script>




@endsection

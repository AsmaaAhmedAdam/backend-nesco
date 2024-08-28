@php
	$lang = session()->get('admin_lang');
    if($lang == null) {
        $lang = 'ar';
    }
	app()->setLocale($lang);
@endphp

@extends('admin.layouts.master')

@section('top_title') {{ trans('home.invoice_details') }}  @endsection

@section('main_title') {{ trans('home.invoice_details') }}  @endsection

@section('header')
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

       .btn.m-btn--hover-brand:not(.btn-secondary):not(.btn-outline-light) { margin-top: -10px }

       label {
         font-size: 18px !important;
         font-weight: 500 !important;
      }


    </style>
@endsection

@section('content')

    @include('flash-message')

    <!--begin::Card-->
    <div class="card card-custom">

        <div class="card-body">


         <div class="user_content" style="margin-bottom:30px">

            @php $user_inv = App\Models\Invoice_User::where('id',$invoice->invoice_user_id)->first(); @endphp

            @if($user_inv != null)

               <label> <b> {{ trans('home.user_name') }} </b> : {{ $user_inv->name }} </label>&nbsp
               <label>&nbsp;,&nbsp;  <b> {{ trans('home.email') }}</b> : {{ $user_inv->email }} </label>
               <label>&nbsp;,&nbsp;  <b> {{ trans('home.mobile') }}</b> : {{ $user_inv->mobile }} </label>

               <br>
               <label>  <b> {{ trans('home.city_name') }} </b> : {{ $user_inv->city != null ? $user_inv->city->{$lang.'_name'} : '' }} </label>
               <label>&nbsp;,&nbsp;  <b> {{ trans('home.address') }} </b> : {{ $user_inv->address }} </label>

            @endif


            <label> &nbsp;,&nbsp; <b> {{ trans('home.s_no') }}	</b> : {{ $invoice->serial_number }} </label>
            <label>&nbsp;,&nbsp;  <b> {{ trans('home.date') }}	</b> : {{ $invoice->operation_date }} </label>
    
            @if(!empty($address))
                    <label>&nbsp;,&nbsp;  <b> {{ trans('home.address') }}	</b> : 
                    @foreach($address as $key => $value)
                        @if($key == 'postal')
                         @break
                        @elseif($key == 'building')
                         building {{$value}},
                        @elseif($key == 'floor')
                         floor {{$value}},
                        @elseif($key == 'flat')
                         flat {{$value}},
                        @elseif($key == 'flat')
                         flat {{$value}},
                        @else
                         {{$value}},
                        @endif
                    @endforeach
                    </label>
            @endif
         
     

         </div>

            <!--begin: Datatable-->

            <table class="table table-bordered table-checkable" id="m_table_1">

               <thead>
                  <tr>
                     <th style="font-weight: bold;font-size:15px"> {{ trans('home.product_name') }}   </th>
                     <th style="font-weight: bold;font-size:15px">  {{ trans('home.quantity') }}  </th>
                     <th style="font-weight: bold;font-size:15px">  {{ trans('home.price') }}   </th>
                     <th style="font-weight: bold;font-size:15px"> {{ trans('home.total') }}    </th>
                  </tr>
               </thead>


               <tbody>

                  @if($Item->count() > 0)
                     @foreach($Item as $value)

                     <tr>

                        <td>
                           @if($value->product != null)
                           <a href="{{ url('admin_panel/products/'.$value->product->id.'/edit') }}" style="color:blue;font-weight:bold">
                              {{ $value->product->{$lang.'_title'} }}
                           </a>
                           @endif
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


                     <!--<tr>-->
                     <!--   <th style="font-weight: bold;font-size:15px">{{ trans('home.sub_total') }} </th>-->
                     <!--   <td colspan="5"> {{ $invoice->total }} {{ trans('home.aed') }} </td>-->
                     <!--</tr>-->

                     <tr>
                        <th style="font-weight: bold;font-size:15px">{{ trans('home.tax') }} </th>
                        <td colspan="5"> {{ $invoice->tax }} {{ trans('home.aed') }} </td>
                     </tr>

                     @if($invoice != null && $invoice->shipping_value > 0)
                     <tr>
                        <th style="font-weight: bold;font-size:15px">
                            {{ trans('home.shipping_value') }}
                        </th>
                        <td colspan="5">{{ $invoice->shipping_value }} {{ trans('home.aed') }}</td>
                     </tr>
                     @endif

                     @if($invoice != null && !empty($invoice->coupon) && $invoice->coupon->value > 0)
                        
                    @php
                     $discount = $invoice->coupon->value;
                     $discount  = ($invoice->coupon->value_type == 'value') ? $discount.' '.trans('home.aed') : $discount.'%';
                    @endphp
                     <tr>
                        <th style="font-weight: bold;font-size:15px">
                            {{ trans('home.coupon_value') }}
                        </th>
                        <td colspan="5">{{ $discount }}</td>
                     </tr>
                     @endif

                     <tr>
                        @php
                        $discount = $percent = null;
                        $total = $invoice->total + ($invoice->tax  / 100) * ($invoice->total) + $invoice->shipping_value;
                        if(!empty($invoice->coupon)) {
                           $discount = $invoice->coupon->value;
                           $total  = ($invoice->coupon->value_type == 'value') ? ($total - $discount) : $total - (($discount / 100) * $total);
                        }
                        @endphp
                        <th style="font-weight: bold;font-size:15px"> {{ trans('home.total') }}	 </th>
                        <td colspan="5"> {{ $total }} {{ trans('home.aed') }} </td>
                     </tr>

                  @endif

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

       $("#m_table_100").DataTable({
           responsive: !0,
           paging: !0,
           orderable: !1
       });



   });

</script>

@endsection



















@php
	$lang = session()->get('admin_lang');
    if($lang == null) {
        $lang = 'ar';
    }
	app()->setLocale($lang);
@endphp


   <table class="table table-bordered table-checkable" id="m_table_1">
      <thead>
         <tr>
            <th> # </th>
            <th> {{ trans('home.user_name') }}  </th>
            <th> {{ trans('home.date') }}   </th>
            {{-- <th> {{ trans('home.count') }}  </th> --}}
            <th> {{ trans('home.sub_total') }} </th>
            <th> {{ trans('home.tax') }} </th>
            <th> {{ trans('home.shipping_value') }} </th>
            <th> {{ trans('home.coupon_value') }} </th>
            <th> {{ trans('home.total') }} </th>
            <th> {{ trans('home.status') }} </th>
            <th> {{ trans('home.tools') }} </th>
         </tr>
      </thead>
      <tbody>

         @php $x = 1; @endphp

         @foreach($Item as $value)

            {{-- @php
               $value->user = App\Models\User::where('id',$value->user_id)->first();
            @endphp --}}

            <tr>
               <td>
                  <a href="{{ url('admin_panel/invoice_details/'.$value->id) }}" style="color:blue;font-weight:bold">
                     {{ $value->serial_number }}
                  </a>
                </td>

                <td>
                  @if($value->user != null)
                  <a href="{{ url('admin_panel/users/'.$value->user->id.'/edit') }}" style="color:blue;font-weight:bold">
                     {{ $value->user->name }}
                  </a>
                  @endif
                </td>

                <td>
                   {{ Carbon\Carbon::parse($value->created_at)->format('Y-m-d H:i') }}
                </td>

                {{-- <td>
                  {{ $value->count_items }}
                </td> --}}

                <td>
                   {{ $value->total }} {{ trans('home.aed') }}
                </td>

                <td>
                    {{ $value->tax }} {{ trans('home.aed') }}
                 </td>

                <td>
                    {{ $value->shipping_value }} {{ trans('home.aed') }}
                 </td>

                  @php
                  $discount = $percent = null;
                  if(!empty($value->coupon)) {
                     $discount = $value->coupon->value;
                     $discount  = ($value->coupon->value_type == 'value') ? $discount.' '.trans('home.aed') : $discount.'%';
                  }
                  @endphp
                 <td>
                    {{ $discount }}
                 </td>

                 <td>
                  @php
                  $discount = $percent = null;
                  $total = $value->total + ($value->tax  / 100) * ($value->total) + $value->shipping_value;
                  if(!empty($value->coupon)) {
                     $discount = $value->coupon->value;
                     $total  = ($value->coupon->value_type == 'value') ? ($total - $discount) : $total - (($discount / 100) * $total);
                  }
                  @endphp
                  {{ $total }} {{ trans('home.aed') }}
                 </td>

                 <td>
                  @php  
                     $class = new ReflectionClass(App\Models\Invoice::class);
                     $constants = array_flip($class->getConstants()); 
                  @endphp
                  {{ $constants[$value->status]}}
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





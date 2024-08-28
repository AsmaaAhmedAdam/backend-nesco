@php
	$lang = session()->get('admin_lang');
    if($lang == null) {
        $lang = 'ar';
    }
	app()->setLocale($lang);
@endphp


@extends('admin.layouts.master')

@section('main_title') {{ trans('home.home') }} @endsection


@section('sub_title') {{ trans('home.home') }} @endsection


@section('content')

<!--begin:: Widgets/Top Products-->
<div class="m-portlet m-portlet--bordered-semi m-portlet--full-height">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    {{ $msg }}
                </h3>
            </div>
        </div>
    </div>

    <div class="m-portlet__body">

    </div>
</div>
<!--end:: Widgets/Top Products-->

@endsection


@section('footer')

    <script>

        (function($){

            @if ($message = Session::get('success'))

                swal({
                    title: "Sweet !",
                    text: "{{ $message }}",
                    imageUrl: '{{ asset('img/sent.jpg') }}'
                });

            @endif

            @if ($message = Session::get('error'))

                swal({
                    title: "Error",
                    text: "{{ $message }}",
                    type: "warning",
                    showConfirmButton: true,
                    confirmButtonClass: "btn-danger",
                });

            @endif


        })(jQuery);

    </script>

@endsection

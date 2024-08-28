@php
	$lang = session()->get('admin_lang');
    if($lang == null) {
        $lang = 'ar';
    }
	app()->setLocale($lang);
@endphp

@extends('admin.layouts.master')

@section('top_title') {{ trans('home.invoices') }}  @endsection

@section('main_title') {{ trans('home.invoices') }}   @endsection

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

    <!--begin::Card-->
    <div class="card card-custom">

        <div class="card-body" id="datatable_content">

            <!--begin: Datatable-->
            @include('admin.invoices.datatable',['Item' => $Item])
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






















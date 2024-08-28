@php
	$lang = session()->get('admin_lang');
    if($lang == null) {
        $lang = 'ar';
    }
	app()->setLocale($lang);

    $setting = App\Models\Setting::first();
@endphp


@extends('admin.layouts.master')

@section('main_title') {{ trans('home.notifications') }} @endsection

@section('top_title')  {{ trans('home.notifications') }}  @endsection




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



            <h3 style="margin-left: 2.5%">
                {{ trans('home.all-notifications') }}
            </h3>

            <hr style="margin-top: 30px;width: 95%;">

            <!--begin::Tabpane-->
            <div class="tab-pane active show p-8" id="topbar_notifications_notifications" role="tabpanel">
                <!--begin::Scroll-->
                <div class="scroll pr-7 mr-n7">

                    @foreach ($Notifications as $notification)

                        <!--begin::Item-->
                        <div class="d-flex align-items-center mb-6">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-40 symbol-light-warning mr-5">
                                <span class="symbol-label">
                                    <i class="fas fa-edit" style="color:#FFA800"></i>
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Text-->
                            <div class="d-flex flex-column font-weight-bold">
                                <a href="{{ asset($notification->url) }}" class="text-dark text-hover-primary mb-1 font-size-lg">
                                    {{ $notification->{$lang.'_title'} }}
                                </a>
                                <a href="{{ asset($notification->url) }}">
                                    <span class="text-muted">
                                        {{ $notification->{$lang.'_description'} }}
                                    </span>
                                </a>
                            </div>
                            <!--end::Text-->
                        </div>
                        <!--end::Item-->

                    @endforeach

                </div>
                <!--end::Scroll-->

            </div>
            <!--end::Tabpane-->


        </div>
    </div>
    <!--end::Card-->


@endsection


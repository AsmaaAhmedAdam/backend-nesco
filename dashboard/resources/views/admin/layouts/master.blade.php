@php
	$lang = session()->get('admin_lang');
    if($lang == null) {
        $lang = 'ar';
    }
	app()->setLocale($lang);

    $setting = App\Models\Setting::first();
@endphp




@php

    $dir = 'left';
    $dir2 = '';
    $prefix = 'en';

    if($lang == 'en') {
        $dir = 'left';
        $dir2 = 'right';
        $prefix = 'ar';
    } else {
        $dir = 'right';
        $dir2 = 'left';
        $prefix = 'en';
    }

@endphp

<!DOCTYPE html>

<html lang="en">
	<!--begin::Head-->
	<head><base href="">
		<meta charset="utf-8" />
		<title> @yield('main_title') </title>
		<meta name="description" content="Metronic admin dashboard live demo. Check out all the features of the admin panel. A large number of settings, additional services and widgets." />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
		<!-- Load font awesome icons -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		@if($lang == 'en')
		<link href="{{asset('files/admin/datatable')}}/datatables.bundle.css" rel="stylesheet" type="text/css" />
		@else
		<link href="{{asset('files/admin/datatable')}}/datatables.bundle.rtl.css" rel="stylesheet" type="text/css" />
		@endif

		@if($lang == 'en')
		<!--begin::Global Theme Styles(used by all pages)-->
		<link href="{{asset('files/admin/css')}}/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="{{asset('files/admin/css')}}/prismjs.bundle.css" rel="stylesheet" type="text/css" />
		<link href="{{asset('files/admin/css')}}/style.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Global Theme Styles-->
		@else
		<!--begin::Global Theme Styles(used by all pages)-->
		<link href="{{asset('files/admin/css')}}/plugins.bundle.rtl.css" rel="stylesheet" type="text/css" />
		<link href="{{asset('files/admin/css')}}/prismjs.bundle.rtl.css" rel="stylesheet" type="text/css" />
		<link href="{{asset('files/admin/css')}}/style.bundle.rtl.css" rel="stylesheet" type="text/css" />
		<!--end::Global Theme Styles-->
		@endif

		<!--begin::Layout Themes(used by all pages)-->
		<link href="{{asset('files/admin/css')}}/base/light.css" rel="stylesheet" type="text/css" />
		<link href="{{asset('files/admin/css')}}//menu/light.css" rel="stylesheet" type="text/css" />
		<link href="{{asset('files/admin/css')}}/brand/dark.css" rel="stylesheet" type="text/css" />
		<link href="{{asset('files/admin/css')}}/aside/dark.css" rel="stylesheet" type="text/css" />
		<!--end::Layout Themes-->

		@if($lang == 'ar')
		<link href="{{asset('files/admin/css')}}/droidarabickufi.css" rel="stylesheet" type="text/css" />
		@endif

		<link href="{{asset('files/bootstrap-sweetalert')}}/sweetalert.css" rel="stylesheet" type="text/css" />

		<link rel="shortcut icon" href="{{asset('logo.png')}}" />

        <style>

			.aside-menu .menu-nav > .menu-item.menu-item-active > .menu-heading .menu-text,
			.aside-menu .menu-nav > .menu-item.menu-item-active > .menu-link .menu-text,
			.menu-item.menu-item-active  i {
				color: #3699FF;
			}

			@if($lang == 'ar')
			body {
				direction: rtl
			}

			body, html, h1, h2, h3, h4, h5, h6, p, a, li, .m-portlet__head-text, .btn-primary {
                font-family: 'DroidArabicKufiRegular', sans-serif !important;
            }
			@endif

			.m-menu__link-badge {
				background: #FFF;
				padding: 0;
				text-align: center;
				height: 25px;
				width: 25px;
				border-radius: 50%;
				line-height: 25px;
				font-weight: bold;
			}

            .notification_btn:hover, .notification_btn:active {
                background-color: transparent !important;
            }


            .scroll.ps > .ps__rail-y > .ps__thumb-y:hover,
            .scroll.ps > .ps__rail-y > .ps__thumb-y:focus {
                width: 8px !important;
            }

            .scroll.ps > .ps__rail-y , .scroll.ps > .ps__rail-y > .ps__thumb-y {
                width: 8px !important;
            }

            @if($lang == 'ar')
                .notfi_ul {
                    padding-right: 10px;
                }
            @endif


            /*
            .scroll.ps > .ps__rail-y:hover > .ps__thumb-y,
            .scroll.ps > .ps__rail-y:focus > .ps__thumb-y,
            .scroll.ps > .ps__rail-y > .ps__thumb-y,
            .scroll.ps > .ps__rail-y > .ps__thumb-y ,
            .ps__rail-y {
                opacity: 1 !important;
            }

            .ps__thumb-y {
                height: 120px !important
            }

            .ps__rail-y {
                display: block !important
            }
            */

            select.form-control {
                padding-top: 4px !important
            }
		</style>

		@yield('header')


	</head>
	<!--end::Head-->


	<!--begin::Body-->
	<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
		<!--begin::Main-->


        <!--begin::Header Mobile-->
		<div id="kt_header_mobile" class="header-mobile align-items-center header-mobile-fixed">

            <!--begin::Logo-->
			<a href="{{ url('admin_panel') }}">
				<img  src="{{asset('logo.png')}}" />
			</a>
			<!--end::Logo-->

			<!--begin::Toolbar-->
			<div class="d-flex align-items-center">

                <!--begin::Aside Mobile Toggle-->
				<button class="btn p-0 burger-icon burger-icon-left" id="kt_aside_mobile_toggle">
					<span></span>
				</button>
				<!--end::Aside Mobile Toggle-->



				<!--begin::Topbar Mobile Toggle-->
				<button class="btn btn-hover-text-primary p-0 ml-2" id="kt_header_mobile_topbar_toggle">
					<span class="svg-icon svg-icon-xl">
						<!--begin::Svg Icon | path:assets/media/svg/icons/General/User.svg-->
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
							<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								<polygon points="0 0 24 0 24 24 0 24" />
								<path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
								<path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
							</g>
						</svg>
						<!--end::Svg Icon-->
					</span>
				</button>
				<!--end::Topbar Mobile Toggle-->

			</div>
			<!--end::Toolbar-->
		</div>
		<!--end::Header Mobile-->


        <div class="d-flex flex-column flex-root">

            <!--begin::Page-->
			<div class="d-flex flex-row flex-column-fluid page">

                <!--begin::Aside-->
				<div class="aside aside-left aside-fixed d-flex flex-column flex-row-auto" id="kt_aside">

                    <!--begin::Brand-->
					<div class="brand flex-column-auto" id="kt_brand">

                        <!--begin::Logo-->
						<a href="{{ url('/admin_panel') }}" class="brand-logo" style="width:100 px !important">
                            	      <img src="{{asset('logo.png')}}" style="width: 150px;"  class="max-h-70px" alt="">

						</a>
						<!--end::Logo-->

						<!--begin::Toggle-->
						<button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
							<span class="svg-icon svg-icon svg-icon-xl">
								<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Angle-double-left.svg-->
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
									<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
										<polygon points="0 0 24 0 24 24 0 24" />
										<path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999)" />
										<path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999)" />
									</g>
								</svg>
								<!--end::Svg Icon-->
							</span>
						</button>
						<!--end::Toolbar-->

					</div>
					<!--end::Brand-->


					<!--begin::Aside Menu-->
					<div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">
						@include('admin.layouts.inc')
					</div>
					<!--end::Aside Menu-->


				</div>
				<!--end::Aside-->


				<!--begin::Wrapper-->
				<div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">

                    <!--begin::Header-->
					<div id="kt_header" class="header header-fixed">
						<!--begin::Container-->
						<div class="container-fluid d-flex align-items-stretch justify-content-between">
							<!--begin::Header Menu Wrapper-->
							<div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
								<!--begin::Header Menu-->
								<div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
									<!--begin::Header Nav-->
									<ul class="menu-nav">

                                        <li class="menu-item menu-item-open menu-item-here menu-item-submenu menu-item-rel menu-item-open menu-item-here menu-item-active" data-menu-toggle="click" aria-haspopup="true">

											{{--
											<a href="javascript:;" class="menu-link menu-toggle">
												<span class="menu-text">Pages</span>
												<i class="menu-arrow"></i>
											</a>
											--}}
											<h3>
												@yield('top_title')
											</h3>

										</li>

									</ul>
									<!--end::Header Nav-->
								</div>
								<!--end::Header Menu-->
							</div>
							<!--end::Header Menu Wrapper-->
							<!--begin::Topbar-->
							<div class="topbar">


								<!--begin::Languages-->
								<div class="dropdown">

									<!--begin::Toggle-->
									<div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
										<a href="{{ asset(request()->segment(1).'/'.$prefix) }}" onclick="location.href='{{ asset(request()->segment(1).'/'.$prefix) }}'" style="font-size: 20px;padding-{{$dir2}}: 15px;font-weight: bold;border-{{$dir2}}: 2px solid #DDD;margin-{{$dir2}}: 10px;">
											@if($lang == 'en') Ar @else En @endif
										</a>
									</div>

								</div>
								<!--end::Languages-->



                                @php
                                    $Notifications = App\Models\Notifications::where('send_to_type','admin')->orderBy('created_at','desc')->get(['id','seen',$lang.'_description','url',$lang.'_title']);
                                    $un_read_notifications_count = App\Models\Notifications::where('send_to_type','admin')->where('seen',0)->get(['id'])->count();
                                @endphp


                                <!--begin::Notifications-->
								<div class="dropdown">


									<!--begin::Toggle-->
									<div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
										<div class="btn btn-icon btn-clean btn-dropdown btn-lg mr-1 pulse pulse-primary notification_btn"
                                            style="padding-{{ $dir2 }}: 8px;padding-{{ $dir }}: 0;font-weight: bold;border-{{ $dir2 }}: 2px solid #DDD;margin-{{ $dir2 }}: 10px !important;padding-top: 0;padding-bottom: 0;border-radius: 0;height: 33px;">
											<i class="far fa-bell" style="color: #3699FF"></i>
											<span class="pulse-ring"></span>
										</div>
									</div>
									<!--end::Toggle-->


									<!--begin::Dropdown-->
									<div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg">
										<form>
											<!--begin::Header-->
											<div class="d-flex flex-column pt-12 bgi-size-cover bgi-no-repeat rounded-top" style="background-image: url({{ asset('bg-1.jpg') }})">
												<!--begin::Title-->
												<h4 class="d-flex flex-center rounded-top">
													<span class="text-white"> {{ trans('home.notifications') }} </span>
													<span class="btn btn-text btn-success btn-sm font-weight-bold btn-font-md ml-2">
                                                        {{ $un_read_notifications_count }}
                                                        {{ trans('home.new') }}
                                                    </span>
												</h4>
												<!--end::Title-->

											</div>
											<!--end::Header-->
											<!--begin::Content-->
											<div class="tab-content">


                                                <!--begin::Tabpane-->
												<div class="tab-pane active show p-8" id="topbar_notifications_notifications" role="tabpanel">
													<!--begin::Scroll-->
													<div class="scroll pr-7 mr-n7" data-scroll="true" data-height="300" data-mobile-height="200">

                                                        @foreach ($Notifications as $notification)

                                                            <!--begin::Item-->
                                                            <div class="d-flex align-items-center mb-6 notfi_ul">
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

                                                    @if($Notifications->count() > 4)
													<!--begin::Action-->
													<div class="d-flex flex-center pt-7">
														<a href="{{ asset('admin_panel/notifications') }}" class="btn btn-light-primary font-weight-bold text-center">
                                                            {{ trans('home.see_all') }}
                                                        </a>
													</div>
													<!--end::Action-->
                                                    @endif
												</div>
												<!--end::Tabpane-->


											</div>
											<!--end::Content-->
										</form>
									</div>
									<!--end::Dropdown-->
								</div>
								<!--end::Notifications-->


								<!--begin::User-->
								<div class="topbar-item">
									<div class="btn btn-icon btn-icon-mobile w-auto btn-clean d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
										<span class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">{{ trans('home.welcome') }},</span>
										<span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3">{{ Auth::guard('admin')->user()->name }}</span>
										<span class="symbol symbol-lg-35 symbol-25 symbol-light-success">
											<span class="symbol-label font-size-h5 font-weight-bold">{{ substr(Auth::guard('admin')->user()->name, 0,1) }}</span>
										</span>
									</div>
								</div>
								<!--end::User-->


							</div>
							<!--end::Topbar-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Header-->


					<!--begin::Content-->
					<div class="content d-flex flex-column flex-column-fluid" id="kt_content">

                        <!--begin::Subheader-->
						<div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
							<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">

                                <!--begin::Info-->
								<div class="d-flex align-items-center flex-wrap mr-2">

									@yield('sub_title')

								</div>
								<!--end::Info-->

							</div>
						</div>
						<!--end::Subheader-->


						<!--begin::Entry-->
						<div class="d-flex flex-column-fluid">
							<!--begin::Container-->
							<div class="container">
								<!--begin::Dashboard-->

                                @yield('content')

								<!--end::Dashboard-->
							</div>
							<!--end::Container-->
						</div>
						<!--end::Entry-->

					</div>
					<!--end::Content-->


					<!--begin::Footer-->
					<div class="footer bg-white py-4 d-flex flex-lg-column" id="kt_footer">
						<!--begin::Container-->
						<div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
							<!--begin::Copyright-->
							<div class="text-dark order-2 order-md-1">
								<span class="text-muted font-weight-bold mr-2">{{ Carbon\Carbon::now()->format('Y') }}©</span>
								<a href="https://mahercp.net/" target="_blank" class="text-dark-75 text-hover-primary">
                                    Web Development mahercp.net
                                </a>
							</div>
							<!--end::Copyright-->

						</div>
						<!--end::Container-->
					</div>
					<!--end::Footer-->




				</div>
				<!--end::Wrapper-->

			</div>
			<!--end::Page-->

		</div>
		<!--end::Main-->



		<!-- begin::User Panel-->
		<div id="kt_quick_user" class="offcanvas offcanvas-right p-10">

            <!--begin::Header-->
			<div class="offcanvas-header d-flex align-items-center justify-content-between pb-5">
				<h3 class="font-weight-bold m-0"> {{ trans('home.profile') }}
				<small class="text-muted font-size-sm ml-2"></small></h3>
				<a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_user_close">
					<i class="ki ki-close icon-xs text-muted"></i>
				</a>
			</div>
			<!--end::Header-->

			<!--begin::Content-->
			<div class="offcanvas-content pr-5 mr-n5">
				<!--begin::Header-->
				<div class="d-flex align-items-center mt-5">
					<div class="symbol symbol-100 mr-5">
						<div class="symbol-label" style="background-image:url('{{asset('files')}}/admin/img/logo/ls.png')"></div>
						<i class="symbol-badge bg-success"></i>
					</div>
					<div class="d-flex flex-column">
						<a href="#" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">{{ Auth::guard('admin')->user()->name }}</a>
						<div class="navi mt-2">
							<a href="#" class="navi-item">
								<span class="navi-link p-0 pb-2">
									<span class="navi-text text-muted text-hover-primary">{{ Auth::guard('admin')->user()->email }}</span>
								</span>
							</a>
							<a href="{{ url('admin_panel/logout') }}" class="btn btn-sm btn-light-primary font-weight-bolder py-2 px-5">
								{{ trans('home.logout') }}
							</a>
						</div>
					</div>
				</div>
				<!--end::Header-->


			</div>
			<!--end::Content-->

		</div>
		<!-- end::User Panel-->





		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop">
			<span class="svg-icon">
				<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Up-2.svg-->
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
					<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						<polygon points="0 0 24 0 24 24 0 24" />
						<rect fill="#000000" opacity="0.3" x="11" y="10" width="2" height="10" rx="1" />
						<path d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z" fill="#000000" fill-rule="nonzero" />
					</g>
				</svg>
				<!--end::Svg Icon-->
			</span>
		</div>
		<!--end::Scrolltop-->





		<script>
            var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";
        </script>


		<!--begin::Global Config(global config for global JS scripts)-->
		<script>
            var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3699FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#E1F0FF", "secondary": "#EBEDF3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };
        </script>
		<!--end::Global Config-->


		<!--begin::Global Theme Bundle(used by all pages)-->
		<script src="{{asset('files/admin/js')}}/plugins.bundle.js"></script>

		<script src="{{asset('files/admin/js')}}/prismjs.bundle.js"></script>
		<script src="{{asset('files/admin/js')}}/scripts.bundle.js"></script>
		<!--end::Global Theme Bundle-->

		<!--begin::Page Scripts(used by this page)-->
		<script src="{{asset('files/admin/js')}}/widgets.js"></script>
		<!--end::Page Scripts-->

		<script src="{{asset('files/admin/datatable')}}/datatables.bundle.js"></script>

		<script src="{{asset('files/bootstrap-sweetalert')}}/sweetalert.js"></script>

		@yield('footer')

        @if($un_read_notifications_count > 0)
        <script>

            $(document).ready(function () {

                $('.notification_btn').click(function() {

                    setTimeout(function() {

                        $.ajax({
                            url: '{{ asset('admin_panel/update_notifications') }}',
                            method: "get",
                            dataType: "json",
                            success: function (response) {}
                        });

                    }, 5000);

                });

            });

        </script>
        @endif


	</body>
	<!--end::Body-->
</html>

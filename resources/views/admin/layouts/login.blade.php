<!DOCTYPE html>

<html lang="en">
<!--begin::Head-->

<head>
    <meta charset="utf-8" />
    <title>Admin Login  | nestrettocoffee </title>
    <meta name="description" content=  " nestrettocoffee Admin" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->

    <!--begin::Page Custom Styles(used by this page)-->
    <link href="{{asset('files')}}/login/css/login-1.css" rel="stylesheet" type="text/css" />
    <!--end::Page Custom Styles-->

    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="{{asset('files')}}/login/css/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('files')}}/login/css/prismjs.bundle.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('files')}}/login/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles-->

    <link href="{{asset('files/bootstrap-sweetalert')}}/sweetalert.css" rel="stylesheet" type="text/css" />

</head>
<!--end::Head-->

<!--begin::Body-->

<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
    <!--begin::Main-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Login-->
        <div class="login login-1 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">

            <!--begin::Aside-->
            <div class="login-aside d-flex flex-column flex-row-auto" style="background-color: #F2C98A;">
                <!--begin::Aside Top-->
                <div class="d-flex flex-column-auto flex-column pt-lg-40 pt-15">
                    <!--begin::Aside header-->
                    <a href="{{ url('admin_panel') }}" class="text-center mb-10">
                        <img src="{{asset('logo.png')}}" style="width: 150px;"  class="max-h-70px" alt="">
                    </a>

                </div>
                <!--end::Aside Top-->
                <!--begin::Aside Bottom-->
                <div class="aside-img d-flex flex-row-fluid bgi-no-repeat bgi-position-y-bottom bgi-position-x-center" style="background-image: url({{asset('files')}}/login/img/login-visual-1.svg)"></div>
                <!--end::Aside Bottom-->
            </div>
            <!--begin::Aside-->

            <!--begin::Content-->
            <div class="login-content flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
                <!--begin::Content body-->
                <div class="d-flex flex-column-fluid flex-center">
                    <!--begin::Signin-->
                    <div class="login-form login-signin">

                        <!--begin::Form-->
                        {!! Form::open(array('url' => 'admin_panel/login')) !!}



                            <!--begin::Title-->
                            <div class="pb-13 pt-lg-0 pt-5">
                                <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Welcome to Nestretto Coffee</h3>

                            </div>
                            <!--begin::Title-->

                            <!--begin::Form group-->
                            <div class="form-group">
                                <label class="font-size-h6 font-weight-bolder text-dark">Email</label>
                                <input class="form-control form-control-solid h-auto py-6 px-6 rounded-lg" type="text" required name="email" autocomplete="off" />
                                @if ($errors->has('email'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                            <!--end::Form group-->

                            <!--begin::Form group-->
                            <div class="form-group">
                                <div class="d-flex justify-content-between mt-n5">
                                    <label class="font-size-h6 font-weight-bolder text-dark pt-5">Password</label>
                                    <a href="{{ url('admin_panel/password/reset') }}" class="text-primary font-size-h6 font-weight-bolder text-hover-primary pt-5">Forgot Password ?</a>
                                </div>
                                <input class="form-control form-control-solid h-auto py-6 px-6 rounded-lg" type="password" name="password" required autocomplete="off" />
                                @if ($errors->has('password'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                            <!--end::Form group-->


                            <!--begin::Form group-->
                            <div class="form-group">
                                <select name="lang" class="form-control form-control-solid h-auto py-6 px-6 rounded-lg" type="text" required>
                                    <option value="en">En</option>
                                    <option value="ar">Ar</option>
                                </select>
                                @if ($errors->has('lang'))
                                <span class="help-block" style="color:red">
                                    <strong>{{ $errors->first('lang') }}</strong>
                                </span>
                                @endif
                            </div>
                            <!--end::Form group-->


                            <div class="form-group">
                                <label class="checkbox mb-0">
										<input type="checkbox" name="remember">
										<span></span>
										<div class="ml-2">Remember me</div>
									</label>
                            </div>

                            <!--begin::Action-->
                            <div class="pb-lg-0 pb-5">
                                <button type="submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">Login</button>
                            </div>
                            <!--end::Action-->

                        {!! Form::close() !!}
                        <!--end::Form-->

                    </div>
                    <!--end::Signin-->



                </div>
                <!--end::Content body-->


                <!--begin::Content footer-->
                <div class="d-flex justify-content-lg-start justify-content-center align-items-end py-7 py-lg-0">
                    <div class="text-dark-50 font-size-lg font-weight-bolder mr-10">
                        <span class="mr-1">{{Carbon\Carbon::parse(Carbon\Carbon::now())->format('Y')}} ©</span>
                        <a href="https://www.ebtasm.com/" target="_blank" class="text-dark-75 text-hover-primary">Web Development mahercp.net</a>
                    </div>
                </div>
                <!--end::Content footer-->

            </div>
            <!--end::Content-->
        </div>
        <!--end::Login-->
    </div>
    <!--end::Main-->

    <script>
        var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";
    </script>

    <!--begin::Global Config(global config for global JS scripts)-->
    <script>
        var KTAppSettings = {
            "breakpoints": {
                "sm": 576,
                "md": 768,
                "lg": 992,
                "xl": 1200,
                "xxl": 1400
            },
            "colors": {
                "theme": {
                    "base": {
                        "white": "#ffffff",
                        "primary": "#3699FF",
                        "secondary": "#E5EAEE",
                        "success": "#1BC5BD",
                        "info": "#8950FC",
                        "warning": "#FFA800",
                        "danger": "#F64E60",
                        "light": "#E4E6EF",
                        "dark": "#181C32"
                    },
                    "light": {
                        "white": "#ffffff",
                        "primary": "#E1F0FF",
                        "secondary": "#EBEDF3",
                        "success": "#C9F7F5",
                        "info": "#EEE5FF",
                        "warning": "#FFF4DE",
                        "danger": "#FFE2E5",
                        "light": "#F3F6F9",
                        "dark": "#D6D6E0"
                    },
                    "inverse": {
                        "white": "#ffffff",
                        "primary": "#ffffff",
                        "secondary": "#3F4254",
                        "success": "#ffffff",
                        "info": "#ffffff",
                        "warning": "#ffffff",
                        "danger": "#ffffff",
                        "light": "#464E5F",
                        "dark": "#ffffff"
                    }
                },
                "gray": {
                    "gray-100": "#F3F6F9",
                    "gray-200": "#EBEDF3",
                    "gray-300": "#E4E6EF",
                    "gray-400": "#D1D3E0",
                    "gray-500": "#B5B5C3",
                    "gray-600": "#7E8299",
                    "gray-700": "#5E6278",
                    "gray-800": "#3F4254",
                    "gray-900": "#181C32"
                }
            },
            "font-family": "Poppins"
        };
    </script>
    <!--end::Global Config-->

    <!--begin::Global Theme Bundle(used by all pages)-->
    <script src="{{asset('files')}}/login/js/plugins.bundle.js"></script>
    <script src="{{asset('files')}}/login/js/prismjs.bundle.js"></script>
    <script src="{{asset('files')}}/login/js/scripts.bundle.js"></script>
    <!--end::Global Theme Bundle-->

    <!--begin::Page Scripts(used by this page)-->
    <script src="{{asset('files')}}/login/js/login-general.js"></script>
    <!--end::Page Scripts-->

    <script src="{{asset('files/bootstrap-sweetalert')}}/sweetalert.js"></script>

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



</body>
<!--end::Body-->

</html>

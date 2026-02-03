<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">
<head>

    <meta charset="utf-8" />
    <title>{{get_app_setting('title')}} | {{Str::title(str_replace('-', ' ', request()->segment(2)))}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset(get_app_setting('favicon'))}}">

    <!-- Layout config Js -->
    <script src="{{(asset('assets/js/layout.js'))}}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('assets/css/custom.min.css')}}" rel="stylesheet" type="text/css" />

</head>

<body>

    <!-- auth-page wrapper -->
    <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
        <div class="bg-overlay"></div>
        <!-- auth-page content -->
        <div class="auth-page-content overflow-hidden pt-lg-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card overflow-hidden card-bg-fill galaxy-border-none">
                            <div class="row justify-content-center g-0">
                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4 auth-one-bg h-100">
                                        <div class="bg-overlay"></div>
                                        <div class="position-relative h-100 d-flex flex-column">
                                            <div class="mb-4">
                                                <a href="index.html" class="d-block">
                                                    <img src="assets/images/logo-light.png" alt="" height="18">
                                                </a>
                                            </div>
                                            <div class="mt-auto">
                                                <div class="mb-3">
                                                    <i class="ri-double-quotes-l display-4 text-success"></i>
                                                </div>

                                                <div id="qoutescarouselIndicators" class="carousel slide" data-bs-ride="carousel">
                                                    <div class="carousel-indicators">
                                                        <button type="button" data-bs-target="#qoutescarouselIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                                        <button type="button" data-bs-target="#qoutescarouselIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                                        <button type="button" data-bs-target="#qoutescarouselIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                                    </div>
                                                    <div class="carousel-inner text-center text-white-50 pb-5">
                                                        <div class="carousel-item active">
                                                            <p class="fs-15">" Great! Clean code, clean design, easy for customization. Thanks very much! "</p>
                                                        </div>
                                                        <div class="carousel-item">
                                                            <p class="fs-15">" The theme is really great with an amazing customer support."</p>
                                                        </div>
                                                        <div class="carousel-item">
                                                            <p class="fs-15">" Great! Clean code, clean design, easy for customization. Thanks very much! "</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end carousel -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->

                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4">
                                        <div class="mb-4">
                                            <div class="avatar-lg mx-auto">
                                                <div class="avatar-title bg-light text-primary display-5 rounded-circle">
                                                    <i class="ri-mail-line"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-muted text-center mx-lg-3">
                                            <h4 class="">Verify Your Email</h4>
                                            <p>Please enter the 4 digit code sent to <span class="fw-semibold">example@abc.com</span></p>
                                        </div>

                                        @if (Session::has('message') && Session::get('class') == 'error')
                                        <div style="z-index: 11">
                                            <div style="border:none;border-radius: 0;" id="borderedToast4" class="toast toast-border-danger overflow-hidden show w-100" role="alert" aria-live="assertive" aria-atomic="true">
                                                <div class="toast-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <i class="ri-alert-line align-middle"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-0">{{Session::get('message')}}</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif


                                        <div class="mt-4">
                                            {{ html()->form('POST', route('admin.2fa.verify.post'))
    ->id('storeForm')
    ->open() }}

<input type="hidden" name="otp" id="otp">

<div class="row justify-content-center">
    @for($i = 1; $i <= 6; $i++)
        <div class="col-2">
            <div class="mb-3">
                <input
                    type="text"
                    class="form-control form-control-lg bg-light border-light text-center"
                    maxlength="1"
                    id="digit{{ $i }}-input"
                    onkeyup="moveToNext({{ $i }}, event)"
                    autocomplete="off"
                    inputmode="numeric"
                >
            </div>
        </div>
    @endfor
</div>

<div class="mt-3 text-center">
    {{ html()->button('Verify OTP')->type('submit')->class('btn btn-success bg-gradient') }}
</div>

{{ html()->form()->close() }}

                                        </div>

                                        <div class="mt-5 text-center">
                                            <p class="mb-0">Didn't receive a code ? <a href="auth-pass-reset-cover.html" class="fw-semibold text-primary text-decoration-underline">Resend</a> </p>
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->
                            </div>
                            <!-- end row -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->

                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer galaxy-border-none">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0">&copy;
                                <script>document.write(new Date().getFullYear())</script> Velzon. Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesbrand
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->

    <!-- JAVASCRIPT -->
    <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>
    <script src="{{asset('assets/libs/feather-icons/feather.min.js')}}"></script>
    <script src="{{asset('assets/js/pages/plugins/lord-icon-2.1.0.js')}}"></script>
    <script src="{{asset('assets/js/plugins.js')}}"></script>

    <script>
const TOTAL_DIGITS = 6;

function getInputElement(index) {
    return document.getElementById('digit' + index + '-input');
}

function moveToNext(index, event) {
    const key = event.keyCode || event.which;
    const input = getInputElement(index);

    // allow only numbers
    if (input.value && !/^\d$/.test(input.value)) {
        input.value = '';
        return;
    }

    // move forward when a digit is entered
    if (input.value.length === 1 && index < TOTAL_DIGITS) {
        getInputElement(index + 1).focus();
    }

    // backspace → move to previous
    if (key === 8 && index > 1 && input.value === '') {
        getInputElement(index - 1).focus();
    }

    // auto submit when last digit filled
    if (index === TOTAL_DIGITS && input.value.length === 1) {
        submitOtp();
    }
}

function submitOtp() {
    let otp = '';

    for (let i = 1; i <= TOTAL_DIGITS; i++) {
        const val = getInputElement(i).value;
        if (val === '') {
            getInputElement(i).focus();
            return;
        }
        otp += val;
    }

    console.log('OTP:', otp);

    // if you have hidden input
    const otpInput = document.getElementById('otp');
    if (otpInput) {
        otpInput.value = otp;
    }

    // submit form
    document.getElementById('storeForm').submit();
}
</script>

</body>
</html>
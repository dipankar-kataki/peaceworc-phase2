<!Doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">
    <head>
        <meta charset="utf-8" />
        <title>Sign In | Peaceworc</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Peaceworc A Caregiving Organisation" name="description" />
        <meta content="Peaceworc" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/logo/logo.png">

        <!-- Layout config Js -->
        <script src="assets/js/layout.js"></script>
        <!-- Bootstrap Css -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
        <!-- custom Css-->
        <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />

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
                            <div class="card overflow-hidden">
                                <div class="row g-0">
                                    <div class="col-lg-6">
                                        <div class="p-lg-5 p-4 auth-one-bg h-100">
                                            <div class="bg-overlay"></div>
                                            <div class="position-relative h-100 d-flex flex-column">
                                                <div class="mb-4">
                                                    <a href="index.html" class="d-block">
                                                        <img src="assets/images/logo/logo-white.png" alt="" height="60">
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
                                                                <p class="fs-15 fst-italic">"Caregiving is a constant learning experience."</p>
                                                            </div>
                                                            <div class="carousel-item">
                                                                <p class="fs-15 fst-italic">"There will come a time when your loved one is gone, and you will find comfort in the fact that you were their caregiver."</p>
                                                            </div>
                                                            <div class="carousel-item">
                                                                <p class="fs-15 fst-italic">"The simple act of caring is heroic."</p>
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
                                            <div>
                                                <h5 class="text-primary">Welcome Back !</h5>
                                                <p class="text-muted">Sign in to continue to Peaceworc.</p>
                                            </div>

                                            <div class="mt-4">
                                                <form id="loginForm">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label for="email" class="form-label">Email</label>
                                                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter email">
                                                    </div>

                                                    <div class="mb-3">
                                                        <div class="float-end">
                                                            <a href="auth-pass-reset-cover.html" class="text-muted">Forgot password?</a>
                                                        </div>
                                                        <label class="form-label" for="password-input">Password</label>
                                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                                            <input type="password" name="password" id="password" class="form-control pe-5 password-input" placeholder="Enter password">
                                                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                                        </div>
                                                    </div>

                                                    <div class="mt-4">
                                                        <button class="btn btn-success w-100" id="signInBtn" type="button">Sign In</button>
                                                    </div>
                                                </form>
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
            <footer class="footer">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-center">
                                <p class="mb-0">&copy;
                                    <script>document.write(new Date().getFullYear())</script> Peaceworc. Crafted with <i class="mdi mdi-heart text-danger"></i> by Ekodus Technologies Pvt. Ltd.
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

        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        {{-- <script src="assets/libs/simplebar/simplebar.min.js"></script> --}}
        {{-- <script src="assets/libs/node-waves/waves.min.js"></script> --}}
        <script src="assets/libs/feather-icons/feather.min.js"></script>
        {{-- <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script> --}}
        {{-- <script src="assets/js/plugins.js"></script> --}}

        {{-- <!-- password-addon init -->
        <script src="assets/js/pages/password-addon.init.js"></script> --}}

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



        <script>
            const signInBtn = document.getElementById('signInBtn');
            signInBtn.addEventListener('click', (e) => {
                e.preventDefault();
                
                signInBtn.innerHTML = 'Please Wait...';
                signInBtn.setAttribute('disabled', true);
                const loginForm = document.getElementById("loginForm");
                const formData = new FormData(loginForm);

                const xhr = new XMLHttpRequest();

                xhr.open('POST', "{{route('admin.login')}}");
                xhr.setRequestHeader('X-CSRF-TOKEN', '{{csrf_token()}}');

                xhr.addEventListener('load', () => {
                    if(xhr.status == 200 && xhr.readyState === 4){

                        const res = JSON.parse(xhr.responseText)
                        if(res.status === 1){

                            setTimeout(() => {
                                signInBtn.innerHTML = 'Login Successfull';
                            }, 500);

                            setTimeout(() => {
                                signInBtn.innerHTML = 'Redirecting....';
                            }, 500);

                            setTimeout(() => {
                                window.location.replace(res.url);
                            }, 1000);
                            
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops!',
                                text: res.message,
                            })
                            signInBtn.removeAttribute('disabled');
                            signInBtn.innerHTML = 'Sign In';

                        }
                        
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops!',
                            text: 'Something Went Wrong. Login failed.',
                        })

                        signInBtn.removeAttribute('disabled');
                        signInBtn.innerHTML = 'Sign In';
                    }
                });
                
                xhr.send(formData);
            });
        </script>
    </body>
</html>
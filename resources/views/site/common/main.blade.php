<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}">

    <link rel="shortcut icon" href="{{ asset('site/image/logo/peaceworc-favicon.png') }}" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="{{ asset('site/plugins/owl/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('site/plugins/owl/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('site/css/main.css') }}">

    @yield('customHeader')

    <title>@yield('siteTitle')</title>
</head>

<body>
    
    <!-- Navbar -->
    @include('site.common.navbar')

        @yield('main')
    <!-- Footer -->
    @include('site.common.footer')

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    <script src="{{ asset('site/plugins/owl/JQuery.js') }}"></script>
    <script src="{{ asset('site/plugins/owl/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('site/plugins/JQueryValidation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('site/plugins/sweetAlert2/sweetAlert2.all.min.js') }}"></script>
    <script src="{{ asset('site/js/main.js') }}"></script>

    @yield('customJs')
</body>

</html>

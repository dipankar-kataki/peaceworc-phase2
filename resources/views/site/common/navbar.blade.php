<nav class="navbar fixed-top navbar-expand-lg navbar-light" id="navbar">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{route('site.index')}}"><img src="{{ asset('site/image/logo/logo-2.png') }}" width="200" alt=""></a>
        <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('site.index')}}/#aboutSection">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('site.index')}}/#servicesBlock">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('site.index')}}/#becomeCaregiver">Become a Caregiver</a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link" href="{{route('site.blog')}}">Blogs</a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{route('site.index')}}/#contactBlock">Get in touch</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

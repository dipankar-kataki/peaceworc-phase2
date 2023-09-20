@extends('site.common.main')

@section('customHeader')
@endsection

@section('siteTitle', 'PeaceWorc | Home')

@section('main')
    <!-- Banner -->
    @include('site.common.banner') 
    @foreach ($site_layout as $layout)

        @if ($layout->module == 'about' && $layout->status == 1)
            <!-- About -->
            <div class="container col-xxl-8 px-4 py-5" id="aboutSection">
                <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
                    <div class="col-10 col-sm-8 col-lg-6 mx-auto leftBlock">
                        <img src="site/image/banner/about.png" class="d-block mx-lg-auto img-fluid" alt="About PeaceWorc"
                            loading="lazy">
                    </div>
                    <div class="col-lg-6 rightBlock">
                        <p class="text-uppercase fw-bold">Welcome to Peaceworc</p>
                        <h1 class="display-5 fw-bold lh-1 mb-3">We Make A Difference in Your Life</h1>
                        <ul>
                            <li>Kindness and perseverance can imbibe the feeling of determination and support during the darkest
                                times. A lone soul would always lookout for a sense of belonging and being cared for. Our
                                professional nurturers and caregivers are sure to make life easier and more enjoyable for you
                                and your family members.</li>

                            <li>We are a trusted provider of quality and compassionate care. An epitome of responsible physical
                                care and emotional support, our caregivers are sure to hold your hand during the trying times.
                            </li>

                            <li>Our caring professionals are true friendly aides, who would hear you out in distress, provide
                                assistance and motivate you in every other way possible to lead a better life.</li>
                        </ul>
                    </div>
                </div>
            </div>      
        @endif

        
        @if ($layout->module == 'service' && $layout->status == 1)
            <!-- Services -->
            <div class="service container" id="servicesBlock">
                <h1 class="fw-light text-center mb-4">Services we offer</h1>
                <div class="owl-carousel owl-theme servicesSlider">
                    <div class="item">
                        <div class="card">
                            <img src="{{ asset('site/image/services/Elderly-Care.png') }}" width="300" alt="Elderly Care">
                            <div class="card-body">
                                <h5 class="card-title text-center">Elderly Care</h5>
                                <p class="card-text">Elderly people are always fascinated by a trusted companion. Old age
                                    and illness are what make the life of the elderly quite challenging. The urge to be cared for
                                    and nurtured. Caring for the elderly, for those who are suffering from any sort of disease,
                                    and assisting them in their day-to-day activities is what our caregivers are good at.</p>
                            </div>
                        </div>
                    </div>

                    <div class="item">
                        <div class="card">
                            <img src="{{ asset('site/image/services/Baby-Care.png') }}" width="300" alt="Baby Care">
                            <div class="card-body">
                                <h5 class="card-title text-center">Baby Care</h5>
                                <p class="card-text">A newborn constantly seeks attention and care. Managing babies and
                                    work at a time can be an untiring task. Juggling work and looking after the newborn is a big
                                    challenge altogether. Our thoroughly professional and trusted caregivers help you ease the
                                    mental pressure, by taking care of the baby while you are at your workplace.</p>
                            </div>
                        </div>
                    </div>

                    <div class="item">
                        <div class="card">
                            <img src="{{ asset('site/image/services/Disabled-Care.png') }}" width="300" alt="Disabled-Care">
                            <div class="card-body">
                                <h5 class="card-title text-center">Disabled Care</h5>
                                <p class="card-text">Our motivated caregivers provide utmost tenderness to the
                                    specially-abled individual. Assisting them in their day-to-day activities, making them learn the basic life
                                    skills, and productively garnering their interests and likelihoods, are some of the
                                    essential traits a caregiver possesses.</p>
                            </div>
                        </div>
                    </div>

                    <div class="item">
                        <div class="card">
                            <img src="{{ asset('site/image/services/Patients-Care.png') }}" width="300" alt="Patients Care">
                            <div class="card-body">
                                <h5 class="card-title text-center">Patients Care</h5>
                                <p class="card-text">Recovering from a prolonged illness takes its due course of time. Good
                                    care at home after hospital leads to a quick and smooth path for a patient's recovery. A
                                    patient's caregiver's work might not only be restricted to just providing proper nourishment
                                    and nutrition, sometimes just reading out their favorite book to them or watching television
                                    together, work wonders for the sick.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>     
        @endif

        @if ($layout->module == 'become_caregiver' && $layout->status == 1)
            <!-- Become a caregiver -->
            <section class="py-5 container" id="becomeCaregiver">
                <div class="row py-lg-5">
                    <div class="col-lg-6 col-md-8 mx-auto">
                        <h1 class="fw-light text-center">Become a Caregiver</h1>
                    </div>
                </div>
                <p>A caregiver need not necessarily be a nursing professional or anyone from the medical field. Anyone and
                    everyone from any class of society can serve mankind. Being compassionate and empathetic towards people can
                    open up opportunities for you as a caregiver. Our caregivers are</p>

                <p>Duties and Responsibilities-</p>

                <ul>
                    <li>The caregiver is in close contact with the person receiving care and should reasonably monitor their
                        health.</li>

                    <li>Caregivers encourage people to leave their homes and step into the outer world for the health benefits
                        of the resulting physical and mental activity. Depending on a person's situation, a walk through their
                        neighborhood or a visit to a park may require planning or have risks, but it is good to do when
                        possible.</li>

                    <li>Caregivers help people with a healthy diet. Right from giving nutrition suggestions based on the
                        recommendations of dietitians, monitoring the body weight, addressing difficulty swallowing or eating,
                        and arranging a pleasant mealtime is what a caregiver's job undermines.</li>

                    <li> Caregivers have a vital role in supporting people with managing their medications at home.</li>

                    <li>Assists with bathing, dressing, and grooming.</li>

                    <li>Measures and records vital signs.</li>
                </ul>
                <div class="leftBlock">
                    <div class="downloadButtons">
                        <button class="btn android mb-2 me-2 d-flex justify-content-center align-items-center btnJoin">
                            <i class="fa-brands fa-google-play"></i>
                            <div>
                                <small>Download for</small> <br> <b>Android Device</b>
                            </div>
                        </button>
                        <button class="btn android mb-2 d-flex justify-content-center align-items-center btnJoin"><i
                                class="fa-brands fa-apple"></i>
                            <div>
                                <small>Download for</small> <br> <b>IOS Device</b>
                            </div>
                        </button>
                    </div>
                </div>
                
                {{-- <button class="btn btnJoin shadow-none">Download Now</button> --}}
            </section>    
        @endif

        @if ($layout->module == 'become_agency' && $layout->status == 1)
            <!-- Become an Agency -->
            <section class="py-5" id="becomeAgency" style="background:#252525;color:#fff;">
                <div class="container">
                    <div class="row py-lg-5">
                        <div class="col-lg-6 col-md-8 mx-auto">
                            <h1 class="fw-light text-center">Become an Agency</h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 col-sm-12">
                            <p>A caregiver need not necessarily be a nursing professional or anyone from the medical field. Anyone and
                                everyone from any class of society can serve mankind. Being compassionate and empathetic towards people can
                                open up opportunities for you as a caregiver. Our caregivers are</p>
                    
                            <p>Duties and Responsibilities-</p>
                    
                            <ul>
                                <li>The caregiver is in close contact with the person receiving care and should reasonably monitor their
                                    health.</li>
                    
                                <li>Caregivers encourage people to leave their homes and step into the outer world for the health benefits
                                    of the resulting physical and mental activity. Depending on a person's situation, a walk through their
                                    neighborhood or a visit to a park may require planning or have risks, but it is good to do when
                                    possible.</li>
                    
                                <li>Caregivers help people with a healthy diet. Right from giving nutrition suggestions based on the
                                    recommendations of dietitians, monitoring the body weight, addressing difficulty swallowing or eating,
                                    and arranging a pleasant mealtime is what a caregiver's job undermines.</li>
                    
                                <li> Caregivers have a vital role in supporting people with managing their medications at home.</li>
                    
                                <li>Assists with bathing, dressing, and grooming.</li>
                    
                                <li>Measures and records vital signs.</li>
                            </ul>


                            <div class="leftBlock">
                                <div class="downloadButtons">
                                    <button class="btn android mb-2 me-2 d-flex justify-content-center align-items-center btnJoin">
                                        <i class="fa-brands fa-google-play"></i>
                                        <div>
                                            <small>Download for</small> <br> <b>Android Device</b>
                                        </div>
                                    </button>
                                    <button class="btn android mb-2 d-flex justify-content-center align-items-center btnJoin"><i
                                            class="fa-brands fa-apple"></i>
                                        <div>
                                            <small>Download for</small> <br> <b>IOS Device</b>
                                        </div>
                                    </button>
                                </div>
                            </div>
                            {{-- <button class="btn btnJoin shadow-none">Download Now</button> --}}
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="agency-mobile-img ">
                                <img src="site/image/banner/agency.png" alt="">
                            </div>
                        </div>
                    </div>
                    
            
                </div>
                
            </section>   
        @endif

        @if ($layout->module == 'testimonial' && $layout->status == 1)
            <!-- Testimonial -->
            <div id="testimonialBlock">
                <section class="pt-5 text-center container">
                    <div class="row">
                        <div class="col-lg-6 col-md-8 mx-auto">
                            <h1 class="fw-light">Reviews from our happy customers</h1>
                            <div class="heading my-3">
                                <span>Average Ratings</span>
                                <div class="reviews">4.5
                                    <div class="reviews-star">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <div class="container my-5 owl-carousel owl-theme testimonialSlider">
                    <div class="card item p-4">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex justify-cntent-center align-items-center">
                                <img src="{{ asset('site/image/banner/avatar.png') }}" width="50" alt="">
                                <p class="fw-bold mb-0 ms-3">Client Name</p>
                            </div>
                            {{-- <div class="review d-flex justify-cntent-center align-items-center">4.0
                                <div class="review-star ms-3">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="far fa-star text-warning"></i>
                                </div>
                            </div> --}}
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam, veniam excepturi accusamus, iste
                            corrupti architecto nobis dolore eligendi incidunt sed tenetur assumenda maxime laboriosam optio ex
                            consequatur quas! Vero, repellat.</p>
                    </div>
                    <div class="card item p-4">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex justify-cntent-center align-items-center">
                                <img src="{{ asset('site/image/banner/avatar.png') }}" width="50" alt="">
                                <p class="fw-bold mb-0 ms-3">Client Name</p>
                            </div>
                            {{-- <div class="review d-flex justify-cntent-center align-items-center">4.0
                                <div class="review-star ms-3">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="far fa-star text-warning"></i>
                                </div>
                            </div> --}}
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam, veniam excepturi accusamus, iste
                            corrupti architecto nobis dolore eligendi incidunt sed tenetur assumenda maxime laboriosam optio ex
                            consequatur quas! Vero, repellat.</p>
                    </div>
                    <div class="card item p-4">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex justify-cntent-center align-items-center">
                                <img src="{{ asset('site/image/banner/avatar.png') }}" width="50" alt="">
                                <p class="fw-bold mb-0 ms-3">Client Name</p>
                            </div>
                            {{-- <div class="review d-flex justify-cntent-center align-items-center">4.0
                                <div class="review-star ms-3">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                            </div> --}}
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam, veniam excepturi accusamus, iste
                            corrupti architecto nobis dolore eligendi incidunt sed tenetur assumenda maxime laboriosam optio ex
                            consequatur quas! Vero, repellat.</p>
                    </div>
                </div>
            </div>  
        @endif

        @if ($layout->module == 'contact_us' && $layout->status == 1)
            <!-- Contact -->
            <div class="container mb-5" id="contactBlock">
                <div class="row py-lg-5">
                    <h1 class="fw-light text-center">Get In Touch</h1>
                </div>

                <div class="row">
                    <!-- Map -->
                    <div class="col-sm-6 leftBlock">
                        <div class="contact-us-img">
                            <img src="{{asset('site/image/photo/contact-us.jpg')}}" alt="contact-us">
                        </div>
                        {{-- <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d114612.57101497147!2d91.63284247523802!3d26.14318577239997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x375a5a287f9133ff%3A0x2bbd1332436bde32!2sGuwahati%2C%20Assam!5e0!3m2!1sen!2sin!4v1645676847133!5m2!1sen!2sin"
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe> --}}
                    </div>

                    <!-- Form -->
                    <div class="col-sm-6 rightBlock mt-5 mt-sm-0">
                        <h2 class="fw-light">Please provide the followings:</h2>
                        <h6 class="fw-light">Labels marked with (<span style="color:red;">*</span>) are mandatory.</h6>
                        <form id="contactForm" action="{{ route('site.contact') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name<sup style="color:red;">*</sup></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address<sup style="color:red;">*</sup></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                    name="email">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject<sup style="color:red;">*</sup></label>
                                <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject"
                                    name="subject">
                                @error('subject')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Your message<sup style="color:red;">*</sup></label>
                                <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message"
                                    rows="3"></textarea>
                                @error('message')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <button type="submit" class="btn shadow-none" id="submitForm">Submit</button>
                        </form>
                    </div>
                </div>
            </div>  
        @endif
    @endforeach
    
@endsection

@section('customJs')
    <script>
        $(document).ready(function() {
            
            jQuery.validator.addMethod("customEmail", function(value, element) {
                return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i.test(
                    value);
            }, "Please enter a valid email address!");

            $("#contactForm").validate({
                rules: {
                    name: "required",
                    email: {
                        required: true,
                        customEmail: true
                    },
                    subject: "required",
                    message: "required",
                },
                messages: {
                    name: "Please enter your name",
                    email: {
                        required: "Please enter your email",
                        customEmail: "Please enter a valid email address",
                    },
                    subject: "Please enter subject",
                    message: "Please enter your message",
                },
                errorElement: "em",
                errorPlacement: function(error, element) {
                    // Add the `invalid-feedback` class to the error element
                    error.addClass("invalid-feedback");
                    error.insertAfter(element);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass("is-invalid").removeClass("is-valid");
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).addClass("is-valid").removeClass("is-invalid");
                },

                submitHandler: function(form) {
                    $("#submitForm").text("Sending...");
                    $('#submitForm').prop("disabled", true);
                    const route = '{{ route('site.contact') }}';
                    $.ajax({
                        cache: false,
                        dataType: "json",
                        method: "post",
                        url: route,
                        data: $("#contactForm").serialize(),
                        success: function(data) {
                            Swal.fire({
                                icon: data.icon,
                                title: data.title,
                                text: data.text,
                            }).then(() => {
                                $("#submitForm").text("Submit");
                                $('#submitForm').prop("disabled", false);
                                $("#contactForm")[0].reset();
                            })
                        },
                        error: function(err) {
                            console.log(err);
                        },
                    });
                },
            });
        });
    </script>
@endsection

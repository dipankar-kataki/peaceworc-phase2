@extends('welcome')
@section('title', ' Agency Complete Profile')
@section('custom-style')
@endsection
    <style>
        .avatar-lg img{
            height: 100px;
            width: 150px;
            object-fit: cover;
            object-position: top;
        } 
    </style>
    
@section('content')
<div class="container-fluid mt-2">
    <div class="profile-foreground position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg">
            <img src="/assets/images/profile-bg.jpg" alt="" class="profile-wid-img" />
        </div>
    </div>
    <div class="pt-4 mb-4 mb-lg-3 pb-lg-4">
        <div class="row g-4">
            <div class="col-auto">
                <div class="avatar-lg">
                    @if ( $agency_details->photo === null)
                        <img src="{{asset('assets/images/companies/company_image.jpg')}}" alt="user-img" class="img-thumbnail rounded-circle" height="100" width="100"/>
                    @else
                        <img src="{{asset($agency_details->photo)}}" alt="user-img" class="img-thumbnail rounded-circle" height="100" width="100"/>
                    @endif
                </div>
            </div>
            <!--end col-->
            <div class="col">
                <div class="p-2">
                    <h3 class="text-white mb-1">{{$agency_details->company_name}} 
                        @if($is_profile_approved === 1)
                            <i class='eos-icons text-success' title="Profile Approved">verified</i>
                           <!-- Dropdown Menu Item Color -->
                            <div class="btn-group fw-bold">
                                <span class="dropdown-toggle" data-bs-toggle="dropdown" id="profileDropdownBtn" aria-expanded="false"></span>
                                <div class="dropdown-menu dropdownmenu-primary">
                                    <a class="dropdown-item" href="#">
                                        <Button class="btn btn-sm btn-default text-danger fw-bold updateApprovalStatus" title="Click To Block Agency" data-id="{{$agency_details->user_id}}" value="0">Block Agency</Button>
                                    </a>
                                </div>
                            </div>
                        @else
                            <i class='eos-icons text-warning' title="Profile Approved Pending">warning</i>
                        @endif
                       
                    </h3>
                    {{-- <p class="text-white-75">Owner & Founder</p> --}}
                    <div class="hstack text-white-50 gap-1">
                        <div class="me-2">
                            <i class='eos-icons eos-36 me-2 text-white-75 align-middle mb-2'>corporate_fare</i>
                                @if ($agency_details->zip_code === null)
                                    <span>Not Available</span>
                                @else
                                    {{$agency_details->street}}, {{$agency_details->city_or_district}}, {{$agency_details->state}}, {{$agency_details->zip_code}}                                  
                                @endif
                            </i><br>
                            <i class='eos-icons eos-36 me-2 text-white-75 align-middle'>schedule</i>
                                Joined-on : {{$agency_details->created_at->format('M-d, Y h:i A')}}
                            </i>
                        </div>
                    </div>
                    <div class="mt-3">
                        @if($is_profile_approved === 0)
                            <Button class="btn btn-sm btn-warning text-black fw-bold updateApprovalStatus" title="Click To Approve Agency" data-id={{$agency_details->user_id}} value="1">Approval Pending</Button>
                        @endif
                        @if($is_profile_approved === 'missing')
                            <Button class="btn btn-sm btn-warning text-black fw-bold">Profile Incomplete !!!</Button>
                        @endif
                    </div>
                </div>
            </div>
            <!--end col-->
            <div class="col-12 col-lg-auto order-last order-lg-0">
                <div class="row text text-white-50 text-center">
                    <div class="col-lg-6 col-4">
                        <div class="p-2">
                            <h4 class="text-white mb-1 fs-14">3.4 <i class='ri-star-fill text-warning fs-12'></i></h4>
                            <p class="fs-15 mb-0">Rating</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-4">
                        <div class="p-2">
                            <h4 class="text-white mb-1 fs-14">25</h4>
                            <a href="javascript:void(0)" style="color:rgba(255,255,255,.5) !important;" class="fs-15 mb-0">Reviews</a>
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->

        </div>
        <!--end row-->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div>
                <div class="d-flex">
                    <!-- Nav tabs -->
                    <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link fs-14 active" data-bs-toggle="tab" href="#overview-tab" role="tab">
                                <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Overview</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-14" data-bs-toggle="tab" href="#authorized-officers-tab" role="tab">
                                <i class="ri-list-unordered d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Authorized Officers</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-14" data-bs-toggle="tab" href="#payments-made" role="tab">
                                <i class="ri-folder-4-line d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Payments Made</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-14" data-bs-toggle="tab" href="#all-reviews" role="tab">
                                <i class="ri-price-tag-line d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Reviews</span>
                            </a>
                        </li>
                        
                    </ul>
                    <div class="flex-shrink-0">
                        <a href="pages-profile-settings.html" class="btn btn-success"><i class="ri-edit-box-line align-bottom"></i> Edit Profile</a>
                    </div>
                </div>
                <!-- Tab panes -->
                <div class="tab-content pt-4 text-muted">
                    <div class="tab-pane active" id="overview-tab" role="tabpanel">
                        <div class="row">
                            <div class="col-xxl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-2">Profile Completion Status</h5>
                                        <div class="progress animated-progress custom-progress progress-label">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{$completion_rate}}%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100">
                                                <div class="label">{{$completion_rate}}%</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Owner Infomation <i class="ri-information-line" title="Owner is the creator of this agency account."></i></h5>
                                        <div class="table-responsive">
                                            <table class="table table-borderless mb-0">
                                                <tbody>
                                                    <tr>
                                                        <th class="ps-0" scope="row"> <i class="ri-user-line"></i> :</th>
                                                        <td class="text-muted fs-12 text-wrap">{{$agency_details->user->name ?? 'Name Missing'}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-0" scope="row"><i class="ri-phone-line"></i> :</th>
                                                        <td class="text-muted fs-12 text-wrap">{{$agency_details->user->phone ?? 'Phone Number Missing'}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-0" scope="row"><i class="ri-mail-line"></i> :</th>
                                                        <td class="text-muted fs-12 text-wrap">{{$agency_details->user->email ?? 'Email Missing'}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-0" scope="row"><i class="ri-shield-user-line"></i> :</th>
                                                        <td class="text-muted fs-12 text-wrap">{{$agency_details->user->role ?? 'Role Missing'}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-0" scope="row"><i class="ri-calendar-line"></i> :</th>
                                                        <td class="text-muted fs-12 text-wrap">{{$agency_details->user->created_at->format('M-d, Y') ?? 'Joined Date Missing'}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->

                            </div>
                            <!--end col-->
                            <div class="col-xxl-9">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Agency Details</h5>
                                        <div class="row">
                                            <div class="col-12">
                                                <p class="mb-1" style="font-weight:500">Company Bio :</p>
                                                <div class="d-flex mt-2 text-align-justify">
                                                    <p class="px-3 fs-13">
                                                        {{$agency_details->about_company ??  'Company Bio Missing'}}
                                                    </p>
                                                </div>
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                        <div class="row">
                                            <p class="mb-1" style="font-weight:500">Basic Information :</p>
                                            <div class="col-md-4">
                                                <div class="d-flex mt-4">
                                                    <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                        <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                            <i class='eos-icons-outlined'>mark_email_read</i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="mb-1">Email Id :</p>
                                                        <h6 class="text-truncate mb-0 fs-12">{{$agency_details->email ??  'Email Id Missing'}}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="d-flex mt-4">
                                                    <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                        <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                            <i class='eos-icons-outlined'>phone_android</i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="mb-1">Phone Number :</p>
                                                        <h6 class="text-truncate mb-0 fs-12">{{$agency_details->phone ??  'Phone Number Missing'}}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="d-flex mt-4">
                                                    <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                        <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                            <i class='eos-icons-outlined'>landscape</i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="mb-1">Legal Structure :</p>
                                                        <h6 class="text-truncate mb-0 fs-12">{{$agency_details->legal_structure ??  'Legal Structure Missing'}}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                        <div class="row mt-2">
                                            
                                            <div class="col-md-4">
                                                <div class="d-flex mt-4">
                                                    <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                        <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                            <i class='eos-icons-outlined'>groups</i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="mb-1">Total Employees :</p>
                                                        <h6 class="text-truncate mb-0 fs-12">{{$agency_details->number_of_employee ??  'Total Employees Count Missing'}}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="d-flex mt-4">
                                                    <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                        <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                            <i class='eos-icons-outlined'>date_range</i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="mb-1">Years In Business :</p>
                                                        <h6 class="text-truncate mb-0 fs-12">{{$agency_details->years_in_business ??  'Years In Business Missing'}}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="d-flex mt-4">
                                                    <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                        <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                            <i class='eos-icons-outlined'>flag</i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="mb-1">Country of Business :</p>
                                                        <h6 class="text-truncate mb-0 fs-12">{{$agency_details->country_of_business ??  'Country of Business Missing'}}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                        <div class="row mt-3">
                                            <p class="mb-1" style="font-weight:500">Financial Information :</p>
                                            <div class="col-md-4">
                                                <div class="d-flex mt-4">
                                                    <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                        <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                            <i class='eos-icons-outlined'>paid</i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="mb-1">Annual Revenue :</p>
                                                        <h6 class="text-truncate mb-0 fs-12">{{$agency_details->annual_business_revenue ??  'Annual Revenue Missing'}}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="d-flex mt-4">
                                                    <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                        <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                            <i class='eos-icons-outlined'>account_balance</i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="mb-1">Organization Type :</p>
                                                        <h6 class="text-truncate mb-0 fs-12">{{$agency_details->organization_type ??  'Organization Type Missing'}}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="d-flex mt-4">
                                                    <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                        <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                            <i class='eos-icons-outlined'><i class='eos-icons-outlined'>badge</i></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="mb-1">Tax Id/EIN Id :</p>
                                                        <h6 class="text-truncate mb-0 fs-12">{{$agency_details->tax_id_or_ein_id ??  'Tax Id/EIN Id Missing'}}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                    </div>
                                    <!--end card-body-->
                                </div><!-- end card -->


                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <div class="tab-pane fade" id="authorized-officers-tab" role="tabpanel">
                        
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Authorized Officer Details</h5>
                                <div class="row">
                                    @foreach ($authorize_officer as $key => $officer)
                                        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                            <div class="d-flex mt-2">
                                                <div class="flex-shrink-0 avatar-xs align-self-top me-3">
                                                    <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                        {{$key + 1}}
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="mb-2">Name : 
                                                        <span class="text-truncate mb-0 fs-12" style="color:#495057;font-weight:500;">{{$officer->name ??  'Name Missing'}}</span>
                                                    </p>
                                                    
                                                    <p class="mb-2">Email Id :
                                                        <span class="text-truncate mb-0 fs-12" style="color:#495057;font-weight:500;">{{$officer->email ??  'Email Id Missing'}}</span>
                                                    </p>
                                                    
                                                    <p class="mb-2">Phone Number :
                                                        <span class="text-truncate mb-0 fs-12" style="color:#495057;font-weight:500;">{{$officer->phone ??  'Email Id Missing'}}</span>
                                                    </p>
                                                    
                                                    <p class="mb-2">Role :
                                                        <span class="text-truncate mb-0 fs-12" style="color:#495057;font-weight:500;">{{$officer->role ??  'Role Missing'}}</span>
                                                    </p>
                                                    <p class="mb-2">Status :
                                                        @if($officer->status === 'OPEN')
                                                            <span class="text-success mb-0 fs-12" style="font-weight:500;">ACTIVE</span><br>
                                                            <Button class="btn btn-sm btn-danger text-black mt-3 fw-bold updateAuthOfficerStatus" title="Click To Suspend/Block Authorized Officer"  data-id="{{$officer->id}}" value="0">Block Officer</Button>
                                                        @else
                                                            <span class="text-danger mb-0 fs-12" style="font-weight:500;">SUSPENDED</span><br>
                                                            <Button class="btn btn-sm btn-success text-black mt-3 fw-bold updateAuthOfficerStatus" title="Click To Unblock Authorized Officer"  data-id="{{$officer->id}}" value="1">Unblock Officer</Button>
                                                        @endif
                                                    </p>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    <!--end col-->
                                </div>
                            </div>
                            <!--end card-body-->
                        </div><!-- end card -->
                    </div>
                    <!--end tab-pane-->
                    <div class="tab-pane fade" id="payments-made" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-4">
                                    <h5 class="card-title flex-grow-1 mb-0">Documents</h5>
                                    <div class="flex-shrink-0">
                                        <input class="form-control d-none" type="file" id="formFile">
                                        <label for="formFile" class="btn btn-danger"><i class="ri-upload-2-fill me-1 align-bottom"></i> Upload File</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table class="table table-borderless align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th scope="col">File Name</th>
                                                        <th scope="col">Type</th>
                                                        <th scope="col">Size</th>
                                                        <th scope="col">Upload Date</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm">
                                                                    <div class="avatar-title bg-soft-primary text-primary rounded fs-20">
                                                                        <i class="ri-file-zip-fill"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="ms-3 flex-grow-1">
                                                                    <h6 class="fs-15 mb-0"><a href="javascript:void(0)">Artboard-documents.zip</a>
                                                                    </h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>Zip File</td>
                                                        <td>4.57 MB</td>
                                                        <td>12 Dec 2021</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a href="javascript:void(0);" class="btn btn-light btn-icon" id="dropdownMenuLink15" data-bs-toggle="dropdown" aria-expanded="true">
                                                                    <i class="ri-equalizer-fill"></i>
                                                                </a>
                                                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink15">
                                                                    <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-eye-fill me-2 align-middle text-muted"></i>View</a></li>
                                                                    <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-download-2-fill me-2 align-middle text-muted"></i>Download</a></li>
                                                                    <li class="dropdown-divider"></li>
                                                                    <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-delete-bin-5-line me-2 align-middle text-muted"></i>Delete</a></li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm">
                                                                    <div class="avatar-title bg-soft-danger text-danger rounded fs-20">
                                                                        <i class="ri-file-pdf-fill"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="ms-3 flex-grow-1">
                                                                    <h6 class="fs-15 mb-0"><a href="javascript:void(0);">Bank Management System</a></h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>PDF File</td>
                                                        <td>8.89 MB</td>
                                                        <td>24 Nov 2021</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a href="javascript:void(0);" class="btn btn-light btn-icon" id="dropdownMenuLink3" data-bs-toggle="dropdown" aria-expanded="true">
                                                                    <i class="ri-equalizer-fill"></i>
                                                                </a>
                                                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink3">
                                                                    <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-eye-fill me-2 align-middle text-muted"></i>View</a></li>
                                                                    <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-download-2-fill me-2 align-middle text-muted"></i>Download</a></li>
                                                                    <li class="dropdown-divider"></li>
                                                                    <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-delete-bin-5-line me-2 align-middle text-muted"></i>Delete</a></li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm">
                                                                    <div class="avatar-title bg-soft-secondary text-secondary rounded fs-20">
                                                                        <i class="ri-video-line"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="ms-3 flex-grow-1">
                                                                    <h6 class="fs-15 mb-0"><a href="javascript:void(0);">Tour-video.mp4</a></h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>MP4 File</td>
                                                        <td>14.62 MB</td>
                                                        <td>19 Nov 2021</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a href="javascript:void(0);" class="btn btn-light btn-icon" id="dropdownMenuLink4" data-bs-toggle="dropdown" aria-expanded="true">
                                                                    <i class="ri-equalizer-fill"></i>
                                                                </a>
                                                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink4">
                                                                    <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-eye-fill me-2 align-middle text-muted"></i>View</a></li>
                                                                    <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-download-2-fill me-2 align-middle text-muted"></i>Download</a></li>
                                                                    <li class="dropdown-divider"></li>
                                                                    <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-delete-bin-5-line me-2 align-middle text-muted"></i>Delete</a></li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm">
                                                                    <div class="avatar-title bg-soft-success text-success rounded fs-20">
                                                                        <i class="ri-file-excel-fill"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="ms-3 flex-grow-1">
                                                                    <h6 class="fs-15 mb-0"><a href="javascript:void(0);">Account-statement.xsl</a></h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>XSL File</td>
                                                        <td>2.38 KB</td>
                                                        <td>14 Nov 2021</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a href="javascript:void(0);" class="btn btn-light btn-icon" id="dropdownMenuLink5" data-bs-toggle="dropdown" aria-expanded="true">
                                                                    <i class="ri-equalizer-fill"></i>
                                                                </a>
                                                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink5">
                                                                    <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-eye-fill me-2 align-middle text-muted"></i>View</a></li>
                                                                    <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-download-2-fill me-2 align-middle text-muted"></i>Download</a></li>
                                                                    <li class="dropdown-divider"></li>
                                                                    <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-delete-bin-5-line me-2 align-middle text-muted"></i>Delete</a></li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm">
                                                                    <div class="avatar-title bg-soft-info text-info rounded fs-20">
                                                                        <i class="ri-folder-line"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="ms-3 flex-grow-1">
                                                                    <h6 class="fs-15 mb-0"><a href="javascript:void(0);">Project Screenshots Collection</a></h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>Floder File</td>
                                                        <td>87.24 MB</td>
                                                        <td>08 Nov 2021</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a href="javascript:void(0);" class="btn btn-light btn-icon" id="dropdownMenuLink6" data-bs-toggle="dropdown" aria-expanded="true">
                                                                    <i class="ri-equalizer-fill"></i>
                                                                </a>
                                                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink6">
                                                                    <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-eye-fill me-2 align-middle"></i>View</a></li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="javascript:void(0);"><i class="ri-download-2-fill me-2 align-middle"></i>Download</a>
                                                                    </li>
                                                                    <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-delete-bin-5-line me-2 align-middle"></i>Delete</a></li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm">
                                                                    <div class="avatar-title bg-soft-danger text-danger rounded fs-20">
                                                                        <i class="ri-image-2-fill"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="ms-3 flex-grow-1">
                                                                    <h6 class="fs-15 mb-0">
                                                                        <a href="javascript:void(0);">Velzon-logo.png</a>
                                                                    </h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>PNG File</td>
                                                        <td>879 KB</td>
                                                        <td>02 Nov 2021</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a href="javascript:void(0);" class="btn btn-light btn-icon" id="dropdownMenuLink7" data-bs-toggle="dropdown" aria-expanded="true">
                                                                    <i class="ri-equalizer-fill"></i>
                                                                </a>
                                                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink7">
                                                                    <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-eye-fill me-2 align-middle"></i>View</a></li>
                                                                    <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-download-2-fill me-2 align-middle"></i>Download</a></li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="javascript:void(0);"><i class="ri-delete-bin-5-line me-2 align-middle"></i>Delete</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="text-center mt-3">
                                            <a href="javascript:void(0);" class="text-success"><i class="mdi mdi-loading mdi-spin fs-20 align-middle me-2"></i> Load more </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!--end tab-pane-->
                    <div class="tab-pane fade" id="all-reviews" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div>
                                            <h5>All Reviews</h5>
                                            <div class="timeline">
                                                @forelse ($reviews as $key => $item)
                                                
                                                    @if ($key % 2 === 0)
                                                        <div class="timeline-item left">
                                                            <i class="icon ri-calendar-line"></i>
                                                            <div class="date">{{ Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</div>
                                                            <div class="content">
                                                                <div class="d-flex">
                                                                    <div class="flex-shrink-0">
                                                                        <img src="{{asset($item->caregiver->photo)}}" alt="caregiver image" class="avatar-sm rounded">
                                                                    </div>
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <h5 class="fs-15">@ {{$item->caregiver->user->name}} <small class="text-muted fs-13 fw-normal">- {{ Carbon\Carbon::parse($item->created_at)->diffForHumans()}}</small></h5>
                                                                        <p class="text-muted mb-2">{{$item->content}}</p>
                                                                       
                                                                        <div class="hstack gap-2">
                                                                            <a class="btn btn-sm btn-light"><span class="me-1">
                                                                                <i class="ri-star-fill text-warning" ></i> {{$item->rating}}</span> 
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="timeline-item right">
                                                            <i class="icon ri-calendar-line"></i>
                                                            <div class="date">{{ Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</div>
                                                            <div class="content">
                                                                <div class="d-flex">
                                                                    <div class="flex-shrink-0">
                                                                        <img src="{{asset($item->caregiver->photo)}}" alt="caregiver image" class="avatar-sm rounded">
                                                                    </div>
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <h5 class="fs-15">@ {{$item->caregiver->user->name}} <small class="text-muted fs-13 fw-normal">- {{ Carbon\Carbon::parse($item->created_at)->diffForHumans()}}</small></h5>
                                                                        <p class="text-muted mb-2">{{$item->content}}</p>
                                                                       
                                                                        <div class="hstack gap-2">
                                                                            <a class="btn btn-sm btn-light"><span class="me-1">
                                                                                <i class="ri-star-fill text-warning" ></i> {{$item->rating}}</span> 
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @empty
                                                    <h6>No Reviews Found :(</h6>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end card-->
                    </div>
                    <!--end tab-pane-->
                </div>
                <!--end tab-content-->
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->

</div>
@endsection

@section('custom-scripts')
    <script>

        $('.updateApprovalStatus').on('click', function(){

            $(this).attr('disabled', true);
            $(this).text('Please Wait...');

            $('#profileDropdownBtn').text('Please Wait...')
            $('#profileDropdownBtn').css('font-size', '15px')

            let that = $(this);
            $.ajax({
                url:"{{route('agency.access.update.status')}}",
                type:"POST",
                data:{
                    id: that.data('id'),
                    access : that.val(), 
                    updatingFor : 'agency',
                    _token : "{{csrf_token()}}"
                },
                success:function(data){

                    Swal.fire({
                        icon: 'success',
                        title: 'Great!',
                        text: data.message,
                        confirmButtonText : 'Ok'
                    }).then( (res) => {
                        if(res.isConfirmed === true){
                            location.reload(true);
                        }
                    })
                },error:function(xhr, status, error){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: 'Something Went Wrong.',
                    })

                    that.attr('disabled', false);
                    that.text('Block Agency');
                }
            });

        });
    </script>
    <script>

        $('.updateAuthOfficerStatus').on('click', function(){

            $(this).attr('disabled', true);
            $(this).text('Please Wait...');

            let that =  $(this);

            $.ajax({
                url:"{{route('agency.access.update.status')}}",
                type:"POST",
                data:{
                    id : $(this).data('id'),
                    access : $(this).val(), 
                    updatingFor : 'authOfficer',
                    _token : "{{csrf_token()}}"
                },
                success:function(data){
                    Swal.fire({
                        icon: 'success',
                        title: 'Great!',
                        text: data.message,
                        confirmButtonText : 'Ok'
                    }).then( (res) => {
                        if(res.isConfirmed === true){
                            location.reload(true);
                        }
                    })
                },error:function(xhr, status, error){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: 'Something Went Wrong.',
                    })

                    that.attr('disabled', false);
                    that.text(data.btnText);
                }
            });

        });
    </script>
@endsection

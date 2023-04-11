@extends('welcome')
@section('title', 'Agency Complete Job Details')
@section('custom-style')
@endsection
    <style>
        .avatar-lg img{
            height: 100px;
            width: 150px;
            object-fit: cover;
            object-position: top;
        } 

        .profile-wid-bg::before {
            background: linear-gradient(to top, #00040f, #2e3d61) !important;
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
                    @if ( $get_single_job_details->agency_profile->photo === null)
                        <img src="{{asset('assets/images/companies/company_image.jpg')}}" alt="user-img" class="img-thumbnail rounded-circle" height="100" width="100"/>
                    @else
                        <img src="{{asset($get_single_job_details->agency_profile->photo)}}" alt="user-img" class="img-thumbnail rounded-circle" height="100" width="100"/>
                    @endif
                </div>
            </div>
            <!--end col-->
            <div class="col">
                <div class="p-2">
                    <h4 class="text-white mb-1">Job Posted By : {{$get_single_job_details->agency_profile->company_name}} 
                        @if($is_profile_approved === 1)
                            <i class='eos-icons text-success' title="Profile Approved">verified</i>
                        @endif
                       
                    </h4>
                    {{-- <p class="text-white-75">Owner & Founder</p> --}}
                    <div class="hstack text-white-50 gap-1">
                        <div class="me-2">
                            <i class='eos-icons eos-36 me-2 text-white-75 align-middle mb-2'>corporate_fare</i>
                                @if ($get_single_job_details->agency_profile->zip_code === null)
                                    <span>Not Available</span>
                                @else
                                    {{$get_single_job_details->agency_profile->street}}, {{$get_single_job_details->agency_profile->city_or_district}}, {{$get_single_job_details->agency_profile->state}}, {{$get_single_job_details->agency_profile->zip_code}}                                  
                                @endif
                            </i><br>
                            <i class='eos-icons eos-36 me-2 text-white-75 align-middle'>schedule</i>
                                Joined-on : {{$get_single_job_details->agency->created_at->format('M-d, Y h:i A')}}
                            </i>
                        </div>
                    </div>
                    <div class="mt-3">
                        @if($is_profile_approved === 0)
                            <Button class="btn btn-sm btn-warning text-black fw-bold"></i> Approval Pending</Button>
                        @endif
                        @if($is_profile_approved === 'missing')
                            <Button class="btn btn-sm btn-warning text-black fw-bold">Profile Incomplete !!!</Button>
                        @endif
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
               
                <div class="d-flex flex-row justify-content-end">
                    @if($get_single_job_details->payment_status === 0)
                        <a class="fs-12 btn btn-warning" href="#">
                            Payment Status : PENDING
                        </a>
                    @else
                        <a class="fs-12 btn btn-success" href="#">
                            Payment Status : SUCCESS
                        </a>
                    @endif
                    @if ($get_single_job_details->status === 'Open Job')
                        <span class="d-flex align-self-center mx-2 btn btn-md btn-primary fs-12"><i class="mdi mdi-circle-medium"></i>Job Status : {{$get_single_job_details->status}}</span>
                    @elseif($get_single_job_details->status === 'Ongoing Job')
                        <span class="d-flex align-self-center mx-2 btn btn-md btn-secondary fs-12"><i class="mdi mdi-circle-medium"></i> Job Status : {{$get_single_job_details->status}}</span>
                    @elseif($get_single_job_details->status === 'Completed Job' || $get_single_job_details->status === 'Closed' )
                        <span class="d-flex align-self-center mx-2 btn btn-md btn-success fs-12"><i class="mdi mdi-circle-medium"></i> Job Status : {{$get_single_job_details->status}}</span>
                    @elseif($get_single_job_details->status === 'Deleted' || $get_single_job_details->status === 'Quick Call' )
                        <span class="d-flex align-self-center mx-2 btn btn-md btn-danger fs-12"><i class="mdi mdi-circle-medium"></i> Job Status : {{$get_single_job_details->status}}</span>
                    @elseif($get_single_job_details->status === 'Bidding Started' || $get_single_job_details->status === 'Bidding Ended' || $get_single_job_details->status === 'Upcoming')
                        <span class="d-flex align-self-center mx-2 btn btn-md btn-info fs-12"><i class="mdi mdi-circle-medium"></i> Job Status : {{$get_single_job_details->status}}</span>
                    @elseif($get_single_job_details->status === 'Pending' || $get_single_job_details->status === 'On Hold' )
                        <span class="d-flex align-self-center mx-2 btn btn-md btn-warning fs-12"><i class="mdi mdi-circle-medium"></i> Job Status : {{$get_single_job_details->status}}</span>
                    @elseif($get_single_job_details->status === 'Expired' ||  $get_single_job_details->status === 'Cancelled' )
                        <span class="d-flex align-self-center mx-2 btn btn-md btn-dark fs-12"><i class="mdi mdi-circle-medium"></i> Job Status : {{$get_single_job_details->status}}</span>
                    @endif
                    {{-- <a class="fs-12 btn btn-info mx-3" href="#">
                        Job Status : {{$get_single_job_details->status}}
                    </a> --}}
                </div>
                <!-- Tab panes -->
                <div class="tab-content pt-4 text-muted">
                    
                    <div class="tab-pane active" id="overview-tab" role="tabpanel">
                        <div class="row">
                            <div class="col-xxl-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <p class="mb-1" style="font-weight:500">Title :</p>
                                                <div class="d-flex mt-2 text-align-justify">
                                                    <p class="px-3 fs-13">
                                                        {{$get_single_job_details->title ??  'Job Title Missing'}}
                                                    </p>
                                                </div>
                                                <p class="mb-1" style="font-weight:500">Description :</p>
                                                <div class="d-flex mt-2 text-align-justify">
                                                    <p class="px-3 fs-13">
                                                        {{$get_single_job_details->description ??  'Job Description Missing'}}
                                                    </p>
                                                </div>
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                        <p class="mb-1" style="font-weight:500">Other Information :</p>

                                        <div class="row">
                                            <div class="mt-2 px-2">
                                                <p class="mb-1 mx-2">Patient Details :</p>
                                                <div class="d-flex flex-wrap mt-2">
                                                    @foreach ($get_single_job_details->care_items as $key => $care_item)
                                                        <div class="col-md-4 d-flex mb-3 mx-2">
                                                            <div class="avatar-xs align-self-top me-3">
                                                                <div class="avatar-title bg-light rounded-circle fs-12 text-primary">
                                                                    {{$key + 1}}
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <p class="mb-2">Care Type : 
                                                                    <span class="text-truncate mb-0 fs-12" style="color:#495057;font-weight:500;">{{$get_single_job_details->care_type ??  'Care type Missing'}}</span>
                                                                </p>
                                                                <p class="mb-2">Patient Name : 
                                                                    <span class="text-truncate mb-0 fs-12" style="color:#495057;font-weight:500;">{{$care_item->patient_name ??  'Patient Name Missing'}}</span>
                                                                </p>
                                                                
                                                                <p class="mb-2">Gender :
                                                                    <span class="text-truncate mb-0 fs-12" style="color:#495057;font-weight:500;">{{$care_item->gender ??  'Gender Missing'}}</span>
                                                                </p>
                                                                
                                                                <p class="mb-2">Age :
                                                                    <span class="text-truncate mb-0 fs-12" style="color:#495057;font-weight:500;">{{$care_item->age . ' Yrs' ??  'Age Missing'}}</span>
                                                                </p>
                                                                
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <hr class="mt-4 mx-3" style="width:95%;">

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="d-flex mt-2 px-2">
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="mb-1">Job Start Date And Time :</p>
                                                        <h6 class="text-truncate mb-0 fs-12">{{Carbon\Carbon::parse($get_single_job_details->start_date)->format('M-d, Y') ??  'Job Start Date Missing'}} @ {{Carbon\Carbon::parse($get_single_job_details->start_time)->format('H:i A') ??  'Job Start Time Missing'}}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="d-flex mt-2 px-2">
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="mb-1">Job End Date And Time :</p>
                                                        <h6 class="text-truncate mb-0 fs-12">{{Carbon\Carbon::parse($get_single_job_details->end_date)->format('M-d, Y') ??  'Job End Date Missing'}} @ {{Carbon\Carbon::parse($get_single_job_details->end_time)->format('H:i A') ??  'Job End Time Missing'}}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="d-flex mt-2 px-2">
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="mb-1">Job Amount :</p>
                                                        <h6 class="text-truncate mb-0 fs-12">${{$get_single_job_details->amount ??  'Job Amount Missing'}}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="d-flex mt-2 px-2">
                                                    <div class="flex-grow-1 overflow-hidden wrap">
                                                        <p class="mb-1">Address :</p>
                                                        <h6 class="text-truncate mb-0 fs-12">{{$get_single_job_details->street ??  'Street Missing'}}, {{$get_single_job_details->city ??  'City Missing'}}, {{$get_single_job_details->state ??  'State Missing'}}, {{$get_single_job_details->zip_code ??  'Zip Codde Missing'}}, {{$get_single_job_details->country ??  'Country Missing'}}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="mt-4 mx-3" style="width:95%;">
                                            <div class="col-md-3">
                                                <div class="d-flex mt-2 px-2">
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="mb-1">Medical History :</p>
                                                            
                                                        @foreach ($get_single_job_details->medical_history as $key => $med)
                                                            <ul>
                                                                <li class="mb-0 pb-0" style="line-height: 10px;">{{$med ??  'Medical History Missing'}}</li>
                                                            </ul>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="d-flex mt-2 px-2">
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="mb-1">Expertise :</p>
                                                            
                                                        @foreach ($get_single_job_details->expertise as $key => $expertise)
                                                            <ul>
                                                                <li class="mb-0 pb-0" style="line-height: 10px;">{{$expertise ??  'Expertise Missing'}}</li>
                                                            </ul>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="d-flex mt-2 px-2">
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="mb-1">Other Requirements :</p>
                                                            
                                                        @foreach ($get_single_job_details->other_requirements as $key => $requirements)
                                                            <ul>
                                                                <li class="mb-0 pb-0" style="line-height: 10px;">{{$requirements ??  'Other Requirements Missing'}}</li>
                                                            </ul>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="d-flex mt-2 px-2">
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <p class="mb-1">Check List :</p>
                                                            
                                                        @foreach ($get_single_job_details->check_list as $key => $check_list)
                                                            <ul>
                                                                <li class="mb-0 pb-0" style="line-height: 10px;">{{$check_list ??  'Check List Missing'}}</li>
                                                            </ul>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <!--end row-->
                                    </div>
                                    <!--end card-body-->
                                </div><!-- end card -->


                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    {{-- <div class="tab-pane fade" id="authorized-officers-tab" role="tabpanel">
                        
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Authorized Officer Details</h5>
                                <div class="row">
                                    @foreach ($authorize_officer as $key => $officer)
                                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
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
                    </div> --}}
                    <!--end tab-pane-->
                    {{-- <div class="tab-pane fade" id="payments-made" role="tabpanel">
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
                    </div> --}}
                    
                    <!--end tab-pane-->
                    {{-- <div class="tab-pane fade" id="all-reviews" role="tabpanel">
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
                    </div> --}}
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
    {{-- <script>

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
    </script> --}}
@endsection

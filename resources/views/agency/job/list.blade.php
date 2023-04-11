@extends('welcome')
@section('title', ' All Jobs List')
@section('custom-style')
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0 flex-grow-1">All Jobs</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <table id="agency_list" class="table table-bordered dt-responsive table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 10px;">
                                    <div class="form-check">
                                        <input class="form-check-input fs-15" type="checkbox" id="checkAll" value="option">
                                    </div>
                                </th>
                                <th>SR No.</th>
                                <th>Job Title</th>
                                <th>Start Date & Time</th>
                                <th>End Date & Time</th>
                                <th>Amount</th>
                                <th>Payment Status</th>
                                <th>Job Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($get_all_jobs as $key => $jobs)
                                <tr>
                                    <th scope="row">
                                        <div class="form-check">
                                            <input class="form-check-input fs-15" type="checkbox" name="checkAll" value="option1">
                                        </div>
                                    </th>
                                    <td>{{$key + 1}}</td>
                                    <td>{{$jobs->title}}</td>
                                    <td>{{$jobs->start_date}} @ {{$jobs->start_time}}</td>
                                    <td>{{$jobs->end_date}} @ {{$jobs->end_time}}</td>
                                    <td>{{$jobs->amount}}</td>
                                    <td>
                                        @if($jobs->payment_status === 1)
                                            <a href="javascript:void(0);" class="fs-14 badge text-bg-success">SUCCESSFUL</a>
                                        @elseif($jobs->payment_status === 0)
                                            <a href="javascript:void(0);" class="fs-14 badge text-bg-warning">PENDING</a>
                                        @else
                                            <a href="javascript:void(0);" class="fs-14 badge text-bg-danger">FAILED</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($jobs->status === 'Open Job')
                                            <span class="badge badge-label bg-primary fs-12"><i class="mdi mdi-circle-medium"></i>{{$jobs->status}}</span>
                                        @elseif($jobs->status === 'Ongoing Job')
                                            <span class="badge badge-label bg-secondary fs-12"><i class="mdi mdi-circle-medium"></i> {{$jobs->status}}</span>
                                        @elseif($jobs->status === 'Completed Job' || $jobs->status === 'Closed' )
                                            <span class="badge badge-label bg-success fs-12"><i class="mdi mdi-circle-medium"></i> {{$jobs->status}}</span>
                                        @elseif($jobs->status === 'Deleted' || $jobs->status === 'Quick Call' )
                                            <span class="badge badge-label bg-danger fs-12"><i class="mdi mdi-circle-medium"></i> {{$jobs->status}}</span>
                                        @elseif($jobs->status === 'Bidding Started' || $jobs->status === 'Bidding Ended' || $jobs->status === 'Upcoming')
                                            <span class="badge badge-label bg-info fs-12"><i class="mdi mdi-circle-medium"></i> {{$jobs->status}}</span>
                                        @elseif($jobs->status === 'Pending' || $jobs->status === 'On Hold' )
                                            <span class="badge badge-label bg-warning fs-12"><i class="mdi mdi-circle-medium"></i> {{$jobs->status}}</span>
                                        @elseif($jobs->status === 'Expired' ||  $jobs->status === 'Cancelled' )
                                            <span class="badge badge-label bg-dark fs-12"><i class="mdi mdi-circle-medium"></i> {{$jobs->status}}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a href="{{route('admin.agency.get.all.job', ['id' => encrypt($jobs->id) ])}}" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View Details</a></li>
                                                <li>
                                                    <a class="dropdown-item remove-item-btn">
                                                        <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            
                        </tbody>
                    </table>

                    
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div>
        <!-- end col -->
    </div>
@endsection

@section('custom-scripts')
    <script>
        $(document).ready( function () {
            $('#agency_list').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'pdf',
                    'excel',
                    'print'
                ]
            });
        } );
    </script>
@endsection

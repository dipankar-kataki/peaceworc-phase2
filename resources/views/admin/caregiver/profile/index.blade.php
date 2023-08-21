@extends('welcome')
@section('title', 'Caregiver List')
@section('custom-style')
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0 flex-grow-1">Caregiver List</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <table id="caregiver_list" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr class="text-center">
                                <th data-ordering="false">SL No.</th>
                                <th data-ordering="false">Photo</th>
                                <th data-ordering="false">Name</th>
                                <th data-ordering="false">Email</th>
                                <th data-ordering="false">Phone</th>
                                <th>Gender</th>
                                <th>Joined On</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($get_caregiver_details as $key => $item)

                            
                                <tr class="text-center">
                                    <td>{{$key + 1}}</td>
                                    <td class="d-flex flex-row justify-content-center">
                                        @if ( $item->caregiverProfile === null)
                                            <img src="{{asset('assets/images/users/user-dummy-img.jpg')}}" alt="user-img" class="img-thumbnail rounded-circle" height="50" width="50"/>
                                        @else
                                            <img class="image avatar-xs rounded-circle" src="{{asset($item->caregiverProfile->photo)}}" alt="Caregiver Image">                                            
                                        @endif
                                    </td>
                                    <td><a href="{{route('admin.get.caregiver.details', ['id' => encrypt($item->id) ] )}}" style="color:blue;">{{$item->name}}</a></td>
                                    <td>{{$item->email ?? 'Not Found'}}</td>
                                    <td>{{$item->caregiverProfile->phone ?? 'Not Found'}}</td>
                                    <td>{{$item->caregiverProfile->gender ?? 'Not Found'}}</td>
                                    <td>{{$item->created_at->format('M-d, Y')}}</td>
                                    <td>
                                        @if ($item->status == 1)
                                            <span class="badge badge-soft-success fs-14">ACTIVE</span>
                                        @elseif($item->status == 2)
                                            <span class="badge badge-soft-warning fs-14">SUSPENDED</span>
                                        @elseif($item->status == 3)
                                            <span class="badge badge-soft-danger fs-14">DELETED</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a href="{{route('admin.get.caregiver.details', ['id' => encrypt($item->id) ] )}}" class="dropdown-item">
                                                    <i class="ri-eye-fill align-bottom me-2 text-muted"></i> View Profile</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item remove-item-btn">
                                                        <i class="ri-user-settings-fill align-bottom me-2 text-muted"></i> Block/Suspend
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
            $('#caregiver_list').DataTable({
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

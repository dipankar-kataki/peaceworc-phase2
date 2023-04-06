@extends('welcome')
@section('title', ' Agency List')
@section('custom-style')
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0 flex-grow-1">Agency List</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <table id="agency_list" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr class="text-center">
                                <th scope="col" style="width: 10px;">
                                    <div class="form-check">
                                        <input class="form-check-input fs-15" type="checkbox" id="checkAll" value="option">
                                    </div>
                                </th>
                                <th data-ordering="false">SR No.</th>
                                <th data-ordering="false">Logo</th>
                                <th data-ordering="false">Name</th>
                                <th data-ordering="false">Email</th>
                                <th data-ordering="false">Phone</th>
                                <th>Tax ID/ EIN Number</th>
                                <th>Joined On</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($agency_list as $key => $item)
                                <tr class="text-center">
                                    <th scope="row">
                                        <div class="form-check">
                                            <input class="form-check-input fs-15" type="checkbox" name="checkAll" value="option1">
                                        </div>
                                    </th>
                                    <td>{{$key + 1}}</td>
                                    <td class="d-flex flex-row justify-content-center">
                                        @if ( $item->photo === null)
                                            <img src="{{asset('assets/images/companies/company_image.jpg')}}" alt="user-img" class="img-thumbnail rounded-circle" height="50" width="50"/>
                                        @else
                                            <img class="image avatar-xs rounded-circle" src="{{asset($item->photo)}}" alt="agency logo">                                            
                                        @endif
                                    </td>
                                    <td>{{$item->company_name}}</td>
                                    <td>{{$item->email ?? 'Not Found'}}</td>
                                    <td>{{$item->phone ?? 'Not Found'}}</td>
                                    <td>{{$item->tax_id_or_ein_id ?? 'Not Found'}}</td>
                                    <td>{{$item->created_at->format('M-d, Y')}}</td>
                                    <td>
                                        @if ($item->status == 'ACTIVE')
                                            <span class="badge badge-soft-success fs-14">ACTIVE</span>
                                        @elseif($item->status == 'SUSPENDED')
                                            <span class="badge badge-soft-warning fs-14">SUSPENDED</span>
                                        @elseif($item->status == 'DELETED')
                                            <span class="badge badge-soft-danger fs-14">DELETED</span>
                                        @endif
                                    <td>
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a href="{{route('admin.get.agency.details', ['id' => encrypt($item->user_id) ] )}}" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View Profile</a></li>
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

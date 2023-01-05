@extends('welcome')
@section('title', ' Authorize Officer List')
@section('custom-style')
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0 flex-grow-1">Authorize Officer List</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <table id="agency_list" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 10px;">
                                    <div class="form-check">
                                        <input class="form-check-input fs-15" type="checkbox" id="checkAll" value="option">
                                    </div>
                                </th>
                                <th data-ordering="false">SR No.</th>
                                <th data-ordering="false">Name</th>
                                <th data-ordering="false">Email</th>
                                <th data-ordering="false">Phone</th>
                                <th data-ordering="false">Role</th>
                                <th data-ordering="false">Belongs To Agency</th>
                                <th>Joined On</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($get_auth_officer as $key => $item)
                                <tr>
                                    <th scope="row">
                                        <div class="form-check">
                                            <input class="form-check-input fs-15" type="checkbox" name="checkAll" value="option1">
                                        </div>
                                    </th>
                                    <td>{{$key + 1}}</td>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->email}}</td>
                                    <td>{{$item->phone}}</td>
                                    <td>{{$item->role}}</td>
                                    <td>{{$item->agency->company_name}}</td>
                                    <td>{{$item->created_at->format('M-d, Y')}}</td>
                                    <td>
                                        @if ($item->status == 'OPEN')
                                            <span class="badge badge-soft-success fs-14">OPEN</span>
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
                                                <li><a href="{{route('admin.get.authorize.officer.list', ['id' => $item->id ])}}" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View Profile</a></li>
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

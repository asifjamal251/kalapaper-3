@extends('admin.layouts.app')
@push('links')

@endpush




@section('main')



<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{Str::title(str_replace('-', ' ', request()->segment(2)))}}</h4>

            <div class="page-title-right">
             @can('add_admin')
             <a id="create" data-title="Create Admin" href="javascript:void(0);"  class="btn-sm btn btn-primary btn-label rounded-pill">
                <i class="align-middle bx bx-plus label-icon rounded-pill fs-16 me-2"></i>
                Add {{Str::title(str_replace('-', ' ', request()->segment(2)))}}
            </a>
            @endcan
        </div>


    </div>
</div>
</div>
<!-- end page title -->



<div class="row">
    

    <div class="col-md-6 col-sm-12">
        <div class="card">
            <div class="card-header bg-info-subtle custom-padding">
                <h5 class="mb-0 card-title">Personal Details</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle table-sm border-info table-bordered nowrap" style="width:100%">
                        <tr>
                            <th>Name</th>
                            <td>{{ $admin->name }}</td>
                        </tr>

                        <tr>
                            <th>Username</th>
                            <td>{{ $admin->username }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $admin->email }}</td>
                        </tr>
                        @can('show_password_admin')
                            <tr>
                                <th>Password</th>
                                <td>{{ $admin->plain_password }}</td>
                            </tr>
                        @endcan

                        <tr>
                            <th>Status</th>
                            <td>{!! status($admin->status_id) !!}</td>
                        </tr>

                        <tr>
                            <th>2FA Enabled</th>
                            <td>{!! status($admin->google2fa_enabled) !!}</td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>
    </div>



    <div class="col-md-6 col-sm-12">
        <div class="card">
            <div class="card-header bg-info-subtle custom-padding">
                <h5 class="mb-0 card-title">Allow IPs</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle table-sm border-info table-bordered nowrap" style="width:100%">
                        <tr>
                            <th>IP Enabled</th>
                            <td>{!! status($admin->ip_enabled) !!}</td>
                        </tr>
                        <tr>
                            <th>IP Address</th>
                            <th>Notes</th>
                        </tr>

                        @forelse($admin->ips as $ip)

                        <tr>
                            <td>{{$ip->ip_address}}</td>
                            <td>{{ $ip->notes }}</td>
                        </tr>
                        @empty
                        <tr class="text-center">
                            <td colspan="2">No IP Found.</td>
                        </tr>
                        @endforelse

                    </table>
                </div>
            </div>
        </div>
    </div>


</div><!--end row-->



@endsection


@push('scripts')


@endpush

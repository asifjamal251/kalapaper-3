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
                            <th>Company Name</th>
                            <td>{{ $client->company_name }}</td>
                        </tr>

                        <tr>
                            <th>GST</th>
                            <td>{{ $client->gst }}</td>
                        </tr>

                        <tr>
                            <th>Email</th>
                            <td>{{ collect($client->email)->filter()->implode(', ') ?: 'N/A', }}</td>
                        </tr>

                        <tr>
                            <th>Contact No.</th>
                            <td>{{ collect($client->contact_no)->filter()->implode(', ') ?: 'N/A', }}</td>
                        </tr>

                        <tr>
                            <th>State</th>
                            <td>{{ $client->state }}</td>
                        </tr>


                        <tr>
                            <th>District</th>
                            <td>{{ $client->district }}</td>
                        </tr>

                        <tr>
                            <th>City</th>
                            <td>{{ $client->city }}</td>
                        </tr>

                        <tr>
                            <th>Pincode</th>
                            <td>{{ $client->pincode }}</td>
                        </tr>


                    </table>
                </div>
            </div>
        </div>
    </div>



    <div class="col-md-6 col-sm-12">
        <div class="card">
            <div class="card-header bg-info-subtle custom-padding">
                <h5 class="mb-0 card-title">Status</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle table-sm border-info table-bordered nowrap" style="width:100%">
                        <tr>
                            <th>Status</th>
                            <td>{!! status($client->status_id) !!}</td>
                        </tr>

                        <tr>
                            <th>Stock On Email</th>
                            <td>{!! status($client->stock_on_email) !!}</td>
                        </tr> 

                        <tr>
                            <th>Login Status</th>
                            <td>{!! status($client->login_status) !!}</td>
                        </tr>                        

                    </table>
                </div>
            </div>
        </div>




        @if($client->login_status == 14)

        <div class="card">
            <div class="card-header bg-info-subtle custom-padding">
                <h5 class="mb-0 card-title">Credentials</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle table-sm border-info table-bordered nowrap" style="width:100%">
                        <tr>
                            <th>Username</th>
                            <th>Password</th>
                        </tr>

                        <tr>
                            <td>{{ $client->username }}</td>
                            <td>{{ $client->password_plain }}</td>
                        </tr>                       

                    </table>
                </div>
            </div>
        </div>


        @endif

    </div>



</div><!--end row-->



@endsection


@push('scripts')


@endpush

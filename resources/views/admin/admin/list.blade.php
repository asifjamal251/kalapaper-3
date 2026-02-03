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
             <a id="create" model-size="modal-lg" data-title="Add New Admin" href="javascript:void(0);"  class="btn-sm btn btn-primary btn-label rounded-pill">
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
    <div class="col-lg-12">
        <div class="card">

            <div class="card-body border border border-dashed border-end-0 border-start-0">




                <div class="row g-3">

                    <div class="col-xxl-4 col-sm-6">
                        <div class="search-box">
                            <div class="m-0 form-group{{ $errors->has('filter_search') ? ' has-error' : '' }}">
                                {{ html()->search('filter_search')->class('form-control onKeyup')->id('filterSearch')->placeholder('Search Name, Email, Username') }}
                                <small class="text-danger">{{ $errors->first('filter_search') }}</small>
                            </div>
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>

                    <div class="col-xxl-2 col-sm-6">
                        <div class="m-0 form-group{{ $errors->has('filter_role') ? ' has-error' : '' }}">
                            {{ html()->select('filter_role', App\Models\Role::whereNotIn('id', [1])->get()->pluck('role_name', 'id'))->id('filterRole')->class('form-control js-choice onChange')->placeholder('Role') }}
                            <small class="text-danger">{{ $errors->first('filter_role') }}</small>
                        </div>
                    </div>

                    <div class="col-xxl-2 col-sm-6">
                        <div class="m-0 form-group{{ $errors->has('filter_status') ? ' has-error' : '' }}">
                            {{ html()->select('filter_status', App\Models\Status::whereIn('id', [14, 15])->pluck('name', 'id'))->id('filterStatus')->class('form-control js-choice onChange')->placeholder('Status') }}
                            <small class="text-danger">{{ $errors->first('filter_status') }}</small>
                        </div>
                    </div>

                </div>
            </div>


            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table align-middle datatable table-sm border-secondary table-bordered nowrap" style="width:100%">

                        <thead>
                            <tr>
                                <th>Si</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Email</th>
                                <th>Status</th>
                                @can(['edit_admin', 'delete_admin', 'read_admin'])
                                <th>Action</th>
                                @endcan
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div><!--end col-->
</div><!--end row-->



@endsection


@push('scripts')

<script type="text/javascript">
    $(document).ready(function(){
        var table2 = $('#datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "ordering": true,
            "searching": false,
            "lengthChange": false,
            "lengthMenu": [25],
            'ajax': {
                'url': '{{ route('admin.'.request()->segment(2).'.index') }}',
                'data': function(d) {
                    d._token = '{{ csrf_token() }}';
                    d._method = 'PATCH';
                    d.search = $('#filterSearch').val();
                    d.role = $('#filterRole').val();
                    d.status = $('#filterStatus').val();
                }

            },
            columns: [
                { data: "sn",       orderable: false }, 
                { data: "name",     orderable: true  },
                { data: "username", orderable: true  },
                { data: "role",     orderable: false  },
                { data: "email",    orderable: false  },
                { data: "status",   orderable: false  },
                @can(['edit_admin', 'delete_admin', 'read_admin'])
                { data: "action", orderable: false }
                @endcan
            ],

            order: [[1, 'asc'], [2, 'asc']]

            });


        $('body').on('keyup', '.onKeyup', function(){
            table2.draw('page');
        });

        $('body').on('mouseup', '.onKeyup', function(e){
            var $input = $(this);
            setTimeout(function(){
                if ($input.val() === '') {
                    table2.draw('page');
                }
            }, 1);
        });

        $('body').on('change', '.onChange', function(){
            table2.draw('page');
        });


    });

</script>











{{-- 
<script type="module">
            window.Echo.channel('posts')
                .listen('.create', (data) => {
                    //document.getElementById('datatable').DataTable().draw('page');
                    var table = new DataTable(document.getElementById('datatable'));
                    table.draw('page');

                    console.log('Order status updated: ', data);
                    var d1 = document.getElementById('notification');
                    d1.insertAdjacentHTML('beforeend', '<div class="alert alert-success alert-dismissible fade show"><span><i class="fa fa-circle-check"></i>  '+data.message+'</span></div>');
                });
            </script> --}}
            @endpush

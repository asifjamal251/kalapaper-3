@extends('admin.layouts.app')
@push('links')
<style type="text/css">
    
</style>
@endpush




@section('main')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{Str::title(str_replace('-', ' ', request()->segment(2)))}}</h4>

            <div class="page-title-right">
             @can('add_purchase_order')
             <a href="{{route('admin.'.request()->segment(2).'.create')}}"  class="btn-sm btn btn-primary btn-label rounded-pill">
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

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable" class="table align-middle datatable table-sm border-secondary table-bordered nowrap" style="width:100%">

                            <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>PO No.</th>
                                <th>From</th>
                                <th>Bill To</th>
                                <th>Ship To</th>
                                <th>Consignee</th>
                                <th>Status</th>
                                @can(['edit_purchase_order', 'delete_purchase_order', 'read_purchase_order'])
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    var table2 = $('#datatable').DataTable({
        "drawCallback": function(settings) {
        },
        "ordering": false,
        "searchning": false,
        "processing": true,
        "serverSide": true,
        "lengthMenu": [25],
        'ajax': {
        'url': '{{ route('admin.'.request()->segment(2).'.index') }}',
        'data': function(d) {
            d._token = '{{ csrf_token() }}';
            d._method = 'PATCH';
        }

        },
        "columns": [
            { "data": "sn" },
            { "data": "po_no" },
            { "data": "from" },
            { "data": "bill_to" },
            { "data": "ship_to" },
            { "data": "consignee" },
            { "data": "status" },
            @can(['edit_purchase_order', 'delete_purchase_order', 'read_purchase_order'])
            { data: "action", orderable: false }
            @endcan
        ],

        "createdRow": function(row, data, dataIndex) {
            $(row).addClass('data-row');
            $(row).attr('data-row', JSON.stringify(data));
        }
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












{{-- <script type="module">
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

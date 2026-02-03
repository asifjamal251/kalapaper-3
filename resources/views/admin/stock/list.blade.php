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
                
                <a bg-color="" model-size="modal-md" data-title="Add New {{Str::title(str_replace('-', ' ', request()->segment(2)))}}" data-url="{{route('admin.'.request()->segment(2).'.create.book')}}" href="javascript:void(0)" class="btn-sm btn btn-primary btn-label rounded-pill create">
                    <i class="align-middle bx bx-plus label-icon rounded-pill fs-16 me-2"></i>
                    Book Stock
                </a>
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

                                <td colspan="2" quality class="p-1">
                                    <div class="sm-form-control m-0 form-group{{ $errors->has('filter_quality') ? ' has-error' : '' }}">
                                        {{ html()->select('filter_quality', App\Models\Quality::orderBy('name', 'asc')->get()->pluck('name_with_code', 'id'))->id('filterQuality')->class('form-control js-choice onChange')->placeholder('Choose Quality') }}
                                        <small class="text-danger">{{ $errors->first('filter_quality') }}</small>
                                    </div>
                                </td>

                                <td gsm class="p-1">
                                    <div class="m-0 form-group{{ $errors->has('filter_gsm') ? ' has-error' : '' }}">
                                        {{ html()->text('filter_gsm')->id('filterGSM')->class('form-control form-control-sm onKeyup')->placeholder('GSM') }}
                                        <small class="text-danger">{{ $errors->first('filter_gsm') }}</small>
                                    </div>
                                </td>

                                <td with class="p-1">
                                    <div class="m-0 form-group{{ $errors->has('filter_width') ? ' has-error' : '' }}">
                                        {{ html()->text('filter_width')->id('filterWidth')->class('form-control form-control-sm onKeyup')->placeholder('Width') }}
                                        <small class="text-danger">{{ $errors->first('filter_width') }}</small>
                                    </div>
                                </td>

                                <td weight class="p-1">
                                    <div class="m-0 form-group{{ $errors->has('filter_weight') ? ' has-error' : '' }}">
                                        {{ html()->text('filter_weight')->id('filterWeight')->class('form-control form-control-sm onKeyup')->placeholder('Weight') }}
                                        <small class="text-danger">{{ $errors->first('filter_weight') }}</small>
                                    </div>
                                </td>

                                {{-- <td batch class="p-1">
                                    <div class="m-0 form-group{{ $errors->has('filter_batch') ? ' has-error' : '' }}">
                                        {{ html()->text('filter_batch')->class('form-control form-control-sm onKeyup')->placeholder('Batch') }}
                                        <small class="text-danger">{{ $errors->first('filter_batch') }}</small>
                                    </div>
                                </td> --}}

                                <td hu class="p-1">
                                    <div class="m-0 form-group{{ $errors->has('filter_handling_nunit') ? ' has-error' : '' }}">
                                        {{ html()->text('filter_handling_nunit')->id('filterHU')->class('form-control form-control-sm onKeyup')->placeholder('HU') }}
                                        <small class="text-danger">{{ $errors->first('filter_handling_nunit') }}</small>
                                    </div>
                                </td>

                                <td vehicle class="p-1">
                                    <div class="m-0 form-group{{ $errors->has('filter_vehicle_no') ? ' has-error' : '' }}">
                                        {{ html()->text('filter_vehicle_no')->id('filterVehicleNo')->class('form-control form-control-sm onKeyup')->placeholder('Vehicle No.') }}
                                        <small class="text-danger">{{ $errors->first('filter_vehicle_no') }}</small>
                                    </div>
                                </td>

                                <td challan No class="p-1">
                                    <div class="m-0 form-group{{ $errors->has('filter_challan_no') ? ' has-error' : '' }}">
                                        {{ html()->text('filter_challan_no')->id('filterChallanNo')->class('form-control form-control-sm onKeyup')->placeholder('Challan No.') }}
                                        <small class="text-danger">{{ $errors->first('filter_challan_no') }}</small>
                                    </div>
                                </td>

                                <td challan date class="p-1">
                                    <div class="m-0 form-group{{ $errors->has('filter_challan_date') ? ' has-error' : '' }}">
                                        {{ html()->text('filter_challan_date')->id('filterChallanDate')->class('dateSelector form-control form-control-sm onChange')->placeholder('Challan date') }}
                                        <small class="text-danger">{{ $errors->first('filter_challan_date') }}</small>
                                    </div>
                                </td>

                                <td job card no class="p-1">
                                    <div class="m-0 form-group{{ $errors->has('filter_job_card_no') ? ' has-error' : '' }}">
                                        {{ html()->text('filter_job_card_no')->id('filterjobCardNo')->class('form-control form-control-sm onKeyup')->placeholder('Job Card No.') }}
                                        <small class="text-danger">{{ $errors->first('filter_job_card_no') }}</small>
                                    </div>
                                </td>

                                <td booked date class="p-1">
                                    <div class="m-0 form-group{{ $errors->has('filter_booked_date') ? ' has-error' : '' }}">
                                        {{ html()->text('filter_booked_date')->id('filterBookedDate')->class('dateSelector form-control form-control-sm onChange')->placeholder('Booked Date') }}
                                        <small class="text-danger">{{ $errors->first('filter_booked_date') }}</small>
                                    </div>
                                </td>

                                <td aging class="p-1"></td>

                                <td colspan="2" status class="p-1">
                                    <div class="m-0 sm-form-control form-group{{ $errors->has('filter_status') ? ' has-error' : '' }}">
                                        {{ html()->select('filter_status', App\Models\Status::orderBy('name', 'asc')->whereIn('id', [22, 23, 24])->pluck('name', 'id'))->id('filterStatus')->class('form-control js-choice onChange')->placeholder('Choose Status') }}
                                        <small class="text-danger">{{ $errors->first('filter_status') }}</small>
                                    </div>
                                </td>


                            </tr>

                            <tr>
                                <th>Si</th>
                                <th>Quality</th>
                                <th>GSM</th>
                                <th>Width</th>
                                <th>Weight</th>
                                {{-- <th>Batch</th> --}}
                                <th>Handling Unit</th>
                                <th>Vehicle No</th>
                                <th>Challan No.</th>
                                <th>Challan Date</th>
                                <th>Job Card No.</th>
                                <th>Booked Date</th>
                                <th>Aging</th>
                                <th>Status</th>
                                @can(['edit_inward', 'delete_inward', 'read_inward'])
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
                    d.quality = $('#filterQuality').val();
                    d.gsm = $('#filterGSM').val();
                    d.width = $('#filterWidth').val();
                    d.weight = $('#filterWeight').val();
                    d.handling_unit = $('#filterHU').val();
                    d.vehicle_no = $('#filterVehicleNo').val();
                    d.challan_no = $('#filterChallanNo').val();
                    d.challan_date = $('#filterChallanDate').val();
                    d.job_card_no = $('#filterJobCardNo').val();
                    d.booked_date = $('#filterBookedDate').val();
                    d.status = $('#filterStatus').val();
                }

            },
            columns: [
                { data: "sn", orderable: false },
                { data: "quality", orderable: true },
                { data: "gsm", orderable: true },
                { data: "width", name: "width", orderable: true },
                { data: "weight", orderable: true },
                //{ data: "batch", orderable: false },
                { data: "handling_unit", orderable: false },
                { data: "vehicle_no", orderable: false },
                { data: "challan_no", orderable: false },
                { data: "challan_date", orderable: false },
                { data: "job_card_no", orderable: false },
                { data: "booked_date", orderable: false },
                { data: "aging", orderable: true },
                { data: "status", orderable: false },
                @can(['edit_inward', 'delete_inward', 'read_inward'])
                { data: "action", orderable: false }
                @endcan
            ],

            order: [
                [1, 'asc'],
                [2, 'asc']
            ],

            "createdRow": function(row, data, dataIndex) {
                if(data.item_added === 1){
                    $(row).addClass('bg-warning-subtle');
                }
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

<script>
    $(function () {
        let selectedQualityId = null;
        let touchTimer = null;

        
        $('#quality').jstree({
            core: {
                themes: { responsive: false },
                check_callback: true,
                data: {
                    url: '{{ route('admin.'.request()->segment(2).'.index') }}?type=quality',
                }
            },
            types: {
                "#": {
                    max_depth: 2,
                    valid_children: ["default"]
                },
                "default": {
                    icon: "bx bxs-folder text-primary fs-20"
                },
                "user": {
                    icon: "bx bxs-user text-warning fs-16"
                }
            },
            state: { key: "coatingTreeState" },
            plugins: ["types"]
        });




        $('#quality')
        .on('open_node.jstree', function (e, data) {
            data.instance.set_type(data.node, "folder-opened");
        })
        .on('close_node.jstree', function (e, data) {
            data.instance.set_type(data.node, "folder");
        });


        function showContextMenu(x, y) {
            $('#qualityContextMenu').css({
                display: 'block',
                left: x + 'px',
                top: y + 'px'
            });
        }


        $('#quality').on('contextmenu', '.jstree-anchor', function (e) {
            e.preventDefault();
            selectedQualityId = $(this).closest('li').attr('id');
            showContextMenu(e.pageX, e.pageY);
        });


        $('#quality').on('touchstart', '.jstree-anchor', function (e) {
            const target = this;
            const touch = e.originalEvent.touches[0];

            touchTimer = setTimeout(() => {
                selectedQualityId = $(target).closest('li').attr('id');
                showContextMenu(touch.pageX, touch.pageY);
            }, 700); 
        });

        $('#quality').on('touchend touchmove', '.jstree-anchor', function () {
            clearTimeout(touchTimer); 
        });


        $(document).on('click touchstart', function (e) {
            if (!$(e.target).closest('#qualityContextMenu').length) {
                $('#qualityContextMenu').hide();
            }
        });


        @can('add_quality')
        $(document).on('click', '#qualityCreate', function () {
            $('#qualityContextMenu').hide();
            openModal('/admin/quality/create', 'Add Quality');
        });

        $(document).on('click', '.addRootQuality', function () {
            openModal('/admin/quality/create', 'Add Quality');
        });
        @endcan

        @can('edit_quality')
        $(document).on('click', '#qualityEdit', function () {
            $('#qualityContextMenu').hide();
            openModal('/admin/quality/' + selectedQualityId + '/edit', 'Edit Quality');
        });
        @endcan

        @can('delete_quality')
        $(document).on('click', '#qualityDelete', function () {
            $('#qualityContextMenu').hide();
            deleteModel('/admin/quality/' + selectedQualityId + '/delete');
        });
        @endcan


        function openModal(url, title) {
            $('.editData').remove();
            $('<button>', {
                type: 'button',
                'data-url': url,
                'data-title': title,
                'model-size': 'modal-md',
                class: 'editData',
                style: 'display:none;'
            }).appendTo('body').trigger('click');
        }
    });


    $('body').on('change', '.makeProcessing', function(){
        if($(this).is(':checked')) {
            var id = $(this).val();
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: '{{ route('admin.'.request()->segment(2).'.store')}}',
                data: {'id':id, 'type':'added', '_method': 'POST', '_token': '{{ csrf_token() }}'},
                success:function(response){
                    Toastify({
                        text: response.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        className: response.class,
                    }).showToast();
                    $('.datatable').DataTable().draw('page');
                },
                error:function(error){
                    console.log(error);
                }
            });
        } else {
            var id = $(this).val();
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: '{{ route('admin.'.request()->segment(2).'.store')}}',
                data: {'id':id, 'type':'remove', '_method': 'POST', '_token': '{{ csrf_token() }}'},
                success:function(response){
                    Toastify({
                        text: response.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        className: response.class,
                    }).showToast();
                    $('.datatable').DataTable().draw('page');
                },
                error:function(error){
                    console.log(error);
                }
            });
        }
    });
</script>




<script>
    $('body').on('change', '.addToJobCard', function(){

        var id = $(this).val();
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: '{{ route('admin.'.request()->segment(2).'.store')}}',
            data: {'id':id, 'type':'added', '_method': 'POST', '_token': '{{ csrf_token() }}'},
            success:function(response){
                Toastify({
                    text: response.message,
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    className: response.class,
                }).showToast();
                $('.datatable').DataTable().draw('page');
                $('.itemCount').html(response.count)
            },
            error:function(error){
                console.log(error);
            }
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

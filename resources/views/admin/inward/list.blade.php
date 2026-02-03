@extends('admin.layouts.app')
@push('links')

@endpush




@section('main')

<ul id="qualityContextMenu" class="p-0 dropdown-menu dropdownmenu-secondary" style="min-width:200px; display:none; position:absolute; z-index:10000;">
        @can('add_quality')
        <li>
            <a model-size="modal-normal" class="dropdown-item" href="javascript:void(0)" id="qualityCreate">
                <i class="bx bx-plus me-2"></i> Add New
            </a>
        </li>
        @endcan

        @can('edit_quality')
        <li>
            <a class="dropdown-item" href="javascript:void(0)" id="qualityEdit">
                <i class="ri-pencil-fill me-2"></i> Edit
            </a>
        </li>
        @endcan

        @can('delete_quality')
        <li><hr class="dropdown-divider m-0"></li> <!-- Correct way to add divider -->

        <li>
            <a class="dropdown-item" href="javascript:void(0)" id="qualityDelete">
                <i class="ri-delete-bin-fill me-2"></i> Delete
            </a>
        </li>
        @endcan
    </ul>

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{Str::title(str_replace('-', ' ', request()->segment(2)))}}</h4>

            <div class="page-title-right">
             @can('add_inward')
            <a id="create" data-url="{{ route('admin.inward.create') }}" model-size="modal-md" data-title="Import Inward" href="javascript:void(0);"  class="btn-sm btn btn-primary btn-label rounded-pill">
                <i class="align-middle bx bx-plus label-icon rounded-pill fs-16 me-2"></i>
                Import {{Str::title(str_replace('-', ' ', request()->segment(2)))}}
            </a>
            @endcan
        </div>


    </div>
</div>
</div>
<!-- end page title -->



<div class="row">

    <div class="col-lg-3 mb-3">

            <div class="mb-3 accordion accordion-flush filter-accordion">
                <div class="accordion-item">
                    <div class="w-100 align-items-center d-flex justify-content-between accordion-header border border-dashed border-top-0 border-end-0 border-start-0 mb-2" id="flush-headingBrands">
                        <div class="w-100 justify-content-between accordion-button bg-transparent shadow-none">
                            <span class="w-100 text-muted text-uppercase fs-12 fw-medium">Quality</span>

                            @can('add_quality')
                            @if(App\Models\quality::count() == 0)
                                <a href="javascript:void(0);" class="w-100 text-end addRootQuality text-decoration-underline fw-normal">Add New</a>
                            @endif
                            @endcan
                        </div>

                    </div>
                    <div class="accordion-collapse collapse show" aria-labelledby="flush-quality" data-simplebar data-simplebar-auto-hide="false" data-simplebar-track="success" style="height: 200px;">
                        <div class="tree m-0 ps-3 pb-3 refereshJSTree" id="quality">

                        </div>
                        <input type="hidden" id="qualityValue" value="">
                    </div>
                </div>
            </div>

        </div>


    <div class="col-lg-9">
        <div class="card">

            <div class="card-body border border border-dashed border-end-0 border-start-0">




                <div class="row g-3">

                    <div class="col-xxl-4 col-sm-6">
                        <div class="search-box">
                            <div class="m-0 form-group{{ $errors->has('filter_search') ? ' has-error' : '' }}">
                                {{ html()->search('filter_search')->class('form-control onKeyup')->id('filterSearch')->placeholder('Search Comany Name and GST') }}
                                <small class="text-danger">{{ $errors->first('filter_search') }}</small>
                            </div>
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-sm-6">
                        <div class="search-box">
                            <div class="m-0 form-group{{ $errors->has('filter_location') ? ' has-error' : '' }}">
                                {{ html()->search('filter_location')->class('form-control onKeyup')->id('filterLocation')->placeholder('Search Pincode, City,  District and State') }}
                                <small class="text-danger">{{ $errors->first('filter_location') }}</small>
                            </div>
                            <i class="ri-search-line search-icon"></i>
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
                                <th>Challan Date</th>
                                <th>Challan No.</th>
                                <th>E-way Bill No.</th>
                                <th>Vehicle No.</th>
                                <th>Transport</th>
                                <th>Items</th>
                                <th>Weights</th>
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
                }

            },
            columns: [
                { data: "sn", orderable: false },
                { data: "challan_date", orderable: true },
                { data: "challan_no", orderable: true },
                { data: "e_way_bill_no", orderable: true },
                { data: "vehicle_no", orderable: true },
                { data: "transport", orderable: true },
                { data: "items", orderable: true },
                { data: "weights", orderable: false },
                { data: "status", orderable: false },
                @can(['edit_inward', 'delete_inward', 'read_inward'])
                { data: "action", orderable: false }
                @endcan
            ],

            order: [
                [1, 'asc'],
                [2, 'asc']
            ]

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

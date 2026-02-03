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

            <a bg-color="" model-size="modal-md" data-title="Add New {{Str::title(str_replace('-', ' ', request()->segment(2)))}}" data-url="{{route('admin.'.request()->segment(2).'.add.reel')}}" href="javascript:void(0)" class="btn-sm btn btn-primary btn-label rounded-pill create">
                    <i class="align-middle bx bx-plus label-icon rounded-pill fs-16 me-2"></i>
                    Book Stock
                </a>
        </div>
    </div>
</div>
<!-- end page title -->

{{ html()->form('POST', route('admin.' . request()->segment(2) . '.store'))->attribute('enctype', 'multipart/form-data')->id('store')->open() }}

<div class="card border border-success mb-5">
    <div class="card-body">
        <div class="row">

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                    {{ html()->label('Type', 'type') }}
                    {{ html()->select('type', ['Reel' => 'Reel', 'Sheet' => 'Sheet'])->class('form-control js-choice')->placeholder('Choose Type') }}
                    <small class="text-danger">{{ $errors->first('type') }}</small>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="m-0 form-group{{ $errors->has('ship_to') ? ' has-error' : '' }}">
                    {{ html()->label('Ship To', 'ship_to') }}
                    {{ html()->select('ship_to', [])->id('shipTo')->placeholder('Ship To')->class('form-control') }}
                    <small class="text-danger">{{ $errors->first('ship_to') }}</small>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="m-0 form-group{{ $errors->has('sold_to') ? ' has-error' : '' }}">
                    {{ html()->label('Sold To', 'sold_to') }}
                    {{ html()->select('sold_to', [])->id('soldTo')->placeholder('Sold To')->class('form-control') }}
                    <small class="text-danger">{{ $errors->first('sold_to') }}</small>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="form-group{{ $errors->has('purchase_order') ? ' has-error' : '' }}">
                    {{ html()->label('Purchase Order', 'purchase_order') }}
                    {{ html()->select('purchase_order', App\Models\PurchaseOrder::orderBy('po_number', 'asc')->whereIn('status_id', [1, 18])->pluck('po_number', 'id'))->class('form-control js-choice-search')->placeholder('Purchase Order') }}
                    <small class="text-danger">{{ $errors->first('purchase_order') }}</small>
                </div>
            </div>

        </div>
    </div>
</div>







<div class="report-repeater">
    <div id="kt_docs_repeater_advanced">


        <div data-repeater-list="kt_docs_repeater_advanced">
            @foreach($inward_items as $index => $item)

            <div data-repeater-item class="repeater-row row-{{$index}}">
                <div class="card border border-secondary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between gap-2">

                            {{ html()->hidden('inward_item_id', $item->id) }}

                            <div class="w-50">
                                <div class="form-group{{ $errors->has('item_number') ? ' has-error' : '' }}">
                                    {{ html()->label('Item Number', 'item_number') }}
                                    {{ html()->text('item_number')->class('form-control')->placeholder('Item Number') }}
                                    <small class="text-danger">{{ $errors->first('item_number') }}</small>
                                </div>
                            </div>

                            <div class="w-50">
                                <div class="form-group{{ $errors->has('length_cm') ? ' has-error' : '' }}">
                                    {{ html()->label('Length CM', 'length_cm') }}
                                    <div class="input-group"> 
                                        {{ html()->text('length_cm')->class('form-control')->placeholder('Length CM') }}
                                        <span class="input-group-text bg-white">CM</span>
                                    </div>
                                    <small class="text-danger">{{ $errors->first('length_cm') }}</small>
                                </div>
                            </div>


                            <div class="w-50">
                                <div class="form-group{{ $errors->has('sheet_per_ream') ? ' has-error' : '' }}">
                                    {{ html()->label('Sheet/Ream', 'sheet_per_ream') }}
                                    {{ html()->select('sheet_per_ream', [100 => 100, 150 => 150, 200 => 200], 100)->class('js-choice form-control')->placeholder('Sheet/Ream') }}
                                    <small class="text-danger">{{ $errors->first('sheet_per_ream') }}</small>
                                </div>
                            </div>


                            <div class="w-75 d-none">
                                <div class="form-group{{ $errors->has('length_inch') ? ' has-error' : '' }}">
                                    {{ html()->label('Length Inch', 'length_inch') }}
                                    <div class="input-group"> 
                                        {{ html()->text('length_inch')->class('form-control')->placeholder('Length Inch')->attribute('readonly') }}
                                        <span class="input-group-text bg-white">INCH</span>
                                    </div>
                                    <small class="text-danger">{{ $errors->first('length_inch') }}</small>
                                </div>
                            </div>

                            

                            <div class="w-50">
                                <div class="form-group{{ $errors->has('trim') ? ' has-error' : '' }}">
                                    {{ html()->label('Trim', 'trim') }}
                                    {{ html()->text('trim', 0)->class('form-control')->placeholder('Trim') }}
                                    <small class="text-danger">{{ $errors->first('trim') }}</small>
                                </div>
                            </div>
                            
                            <div class="w-100 ">
                                <div class="fs-12 form-group{{ $errors->has('quality') ? ' has-error' : '' }}">
                                    {{ html()->label('Quality', 'quality')->class('fs-14') }}
                                    {{ html()->select('quality', App\Models\Quality::orderBy('name', 'asc')->get()->pluck('name_with_code', 'id'), $item->quality_id)->class('form-control js-choice')->placeholder('Choose Quality')->attribute('readonly') }}
                                    <small class="text-danger">{{ $errors->first('quality') }}</small>
                                </div>
                            </div>

                            <div class="w-25">
                                <div class="form-group{{ $errors->has('gsm') ? ' has-error' : '' }}">
                                    {{ html()->label('GSM', 'gsm') }}
                                    {{ html()->text('gsm', $item->gsm)->class('form-control')->placeholder('GSM')->attribute('readonly') }}
                                    <small class="text-danger">{{ $errors->first('gsm') }}</small>
                                </div>
                            </div>

                            <div class="w-25">
                                <div class="form-group{{ $errors->has('width') ? ' has-error' : '' }}">
                                    {{ html()->label('Width', 'width') }}
                                    {{ html()->text('width', $item->width)->class('form-control')->placeholder('Width')->attribute('readonly') }}
                                    <small class="text-danger">{{ $errors->first('width') }}</small>
                                </div>
                            </div>

                            

                        </div>





                        <div class="d-flex justify-content-between gap-2">

                            <div class="w-25">
                                <div class="form-group{{ $errors->has('weight') ? ' has-error' : '' }}">
                                    {{ html()->label('Weight', 'weight') }}
                                    {{ html()->text('weight', $item->weight)->class('form-control')->placeholder('Weight')->attribute('readonly') }}
                                    <small class="text-danger">{{ $errors->first('weight') }}</small>
                                </div>
                            </div>

                            <div class="w-25">
                                <div class="form-group{{ $errors->has('ream_weight') ? ' has-error' : '' }}">
                                    {{ html()->label('Ream Weight', 'ream_weight') }}
                                    {{ html()->text('ream_weight')->class('form-control')->placeholder('Ream Weight')->attribute('readonly') }}
                                    <small class="text-danger">{{ $errors->first('ream_weight') }}</small>
                                </div>
                            </div>

                            @php
                                $selectedWastageId = \App\Models\Wastage::whereRaw('ROUND(width,2) = ROUND(?,2)', [$item->width])->value('id');
                            @endphp

                            <div class="w-25">
                                <div class="{{ $loop->last ? 'wastage-last' : ''}} form-group{{ $errors->has('wastage') ? ' has-error' : '' }}">
                                    {{ html()->label('Wastage', 'wastage-'.$index) }}
                                    {{ html()->select('wastage', App\Models\Wastage::orderBy('width', 'asc')->pluck('width', 'id'), $selectedWastageId)->class('form-control js-choice-search')->id('wastage-'.$index) }}
                                    <small class="text-danger">{{ $errors->first('wastage') }}</small>
                                </div>
                            </div>


                            <div class="w-25">
                                <div class="form-group{{ $errors->has('bundle_pack') ? ' has-error' : '' }}">
                                    {{ html()->label('Bundle Pack', 'bundle_pack') }}
                                    {{ html()->text('bundle_pack')->class('form-control')->placeholder('Bundle Pack')->attribute('readonly') }}
                                    <small class="text-danger">{{ $errors->first('bundle_pack') }}</small>
                                </div>
                            </div>

                            <div class="w-50">
                                <div class="form-group{{ $errors->has('handling_unit') ? ' has-error' : '' }}">
                                    {{ html()->label('Handling Unit', 'handling_unit') }}
                                    {{ html()->text('handling_unit', $item->handling_unit)->class('form-control')->placeholder('Handling Unit')->attribute('readonly') }}
                                    <small class="text-danger">{{ $errors->first('handling_unit') }}</small>
                                </div>
                            </div>

                            <div class="w-50">
                                <div class="form-group{{ $errors->has('purchase_order_item') ? ' has-error' : '' }}">
                                    {{ html()->label('PO Item', 'poItem-'.$item->id) }}
                                    {{ html()->select('purchase_order_item', [])->id('poItem-'.$item->id)->class('form-control poItem')->placeholder('PO Item') }}
                                    <small class="text-danger">{{ $errors->first('purchase_order_item') }}</small>
                                </div>
                            </div>

               



                            <div style="width:50px;">
                                <div class="text-end">
                                    <button data-repeater-delete type="button" data-id="{{$item->id}}" class="btn-labels btn btn-danger" style="margin-top: 23px;">
                                        <i class="label-icon ri-delete-bin-fill"></i>
                                    </button>
                                </div>
                            </div>

                        </div>


                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- <div class="d-flex justify-content-end align-items-center mb-3">
            <div class="form-group m-0">
                <button data-repeater-create type="button" class="btn-label btn btn-info text-end btn-sm">
                    <i class="label-icon align-middle fs-16 me-2 bx bx-plus-circle"></i> Add New Row
                </button>
            </div>
        </div> --}}
    </div>

</div>




<div class="col-md-12 col-sm-12">
    <div class="mt-4 form-group">
        {{ html()->button('Save Details')->type('button')->class('btn btn-success bg-gradient')->attribute('onclick = store(this)') }}
    </div>
</div>
{{ html()->form()->close() }}
@endsection


@push('scripts')

<script type="text/javascript" src="{{asset('assets/js/pages/form-repeater.js')}}"></script>
<script type="text/javascript">


    $('#kt_docs_repeater_advanced').repeater({
        show: function () {
            var $row = $(this);

            $row.find('small.text-danger').html('');
            $row.find('.form-group').removeClass('has-error');

            $row.slideDown('fast', function () {
                $row.find('select[name*="[quality]"]').first().focus();
            });



            $row.find('.js-choice').each(function () {
                if (!this.choicesInstance) {
                    this.choicesInstance = new Choices(this, {
                        allowHTML: true,
                        searchEnabled: false
                    });
                }
            });
        },

        hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
        }
    });




    $(document).ready(function () {
        getParty('#froms', false, 'From', 'firm');
        getParty('#billTo', false, 'Bill To', 'client');
        getParty('#shipTo', false, 'Ship To', 'client');
        getParty('#consignee', false, 'Consignee', 'client');
        getParty('#soldTo', false, 'Sold To', 'client');

        $('body').on('change', '[name="purchase_order"]', function () {

            const poId = $(this).val();

            $('[data-repeater-item]').each(function () {
                const row = $(this);

                const quality = row.find('[name="quality"]').val();
                const gsm = row.find('[name="gsm"]').val();
                const width = row.find('[name="width"]').val();

                const $poItemSelect = row.find('.poItem');

                // clear old value
                $poItemSelect.val(null).trigger('change');

                getPOItem($poItemSelect, false, poId, quality, gsm, width);
            });

        });
    });


    function cmToInches(cm) {
        let inches = parseFloat(cm) / 2.54;
        return isNaN(inches) ? '' : inches.toFixed(1);
    }

    function netReamWeight(number) {
        let beforeDecimal = Math.floor(number);
        let decimalPart = number - beforeDecimal;
        let decimalPlaces = Math.floor(decimalPart * 100);
        let secondDigit = decimalPlaces % 10;
        let firstDigit = Math.floor(decimalPlaces / 10);

        if (secondDigit >= 5) firstDigit++;

        if (firstDigit >= 10) {
            beforeDecimal++;
            firstDigit = 0;
        }

        let roundedNumber = beforeDecimal + firstDigit / 10;
        return roundedNumber.toFixed(1);
    }

    function reamWeightCalc(lengthCm, width, gsm, sheetPerReam, trim) {
        lengthCm = parseFloat(lengthCm) || 0;
        width = parseFloat(width) || 0;
        gsm = parseFloat(gsm) || 0;
        sheetPerReam = parseFloat(sheetPerReam) || 0;
        trim = parseFloat(trim) || 0;

        let finalWidth = width - trim;
        if (finalWidth < 0) finalWidth = 0;

        let reamWeight = (lengthCm * finalWidth * gsm * sheetPerReam) / 20000;
        reamWeight = reamWeight / 500;

        return netReamWeight(reamWeight);
    }

    function customRound(number, reamWeight) {
        const integerPart = Math.floor(number);
        const decimalPart = number - integerPart;

        if (decimalPart >= 0.1 && decimalPart <= 0.9) {
            let checkWeight = reamWeight * integerPart;
            if (checkWeight <= 65) return integerPart;
            return integerPart - 1;
        } else {
            let integerPartNew = Math.round(number);
            let checkWeight = reamWeight * integerPartNew;
            if (checkWeight <= 65) return integerPartNew;
            return integerPartNew - 1;
        }
    }

    function bundlePacket(reamWeight) {
        reamWeight = parseFloat(reamWeight) || 0;
        let targetValue = 65.0;

        if (reamWeight <= 0) return '';

        let packet_count = customRound(targetValue / reamWeight, reamWeight);

        if (packet_count > 7) packet_count = 7;
        if (packet_count < 1) packet_count = 1;

        return packet_count;
    }

    function calculateRepeaterRow($row) {
        let lengthCm = $row.find('input[name$="[length_cm]"]').val();
        let width = $row.find('input[name$="[width]"]').val();
        let gsm = $row.find('input[name$="[gsm]"]').val();
        let trim = $row.find('input[name$="[trim]"]').val();
        let sheetPerReam = $row.find('select[name$="[sheet_per_ream]"]').val();


        $row.find('input[name$="[length_inch]"]').val(cmToInches(lengthCm));

        // ream weight
        let reamWeight = reamWeightCalc(lengthCm, width, gsm, sheetPerReam, trim);
        $row.find('input[name$="[ream_weight]"]').val(reamWeight);

        // bundle pack
        let bundle = bundlePacket(reamWeight);
        $row.find('input[name$="[bundle_pack]"]').val(bundle);
    }

    $(document).on('keyup change', 
        'input[name$="[length_cm]"], input[name$="[width]"], input[name$="[gsm]"], input[name$="[trim]"], select[name$="[sheet_per_ream]"]',
        function () {

            let $row = $(this).closest('[data-repeater-item]');
            calculateRepeaterRow($row);
        }
    );

    $(document).ready(function () {
        $('[data-repeater-item]').each(function () {
            calculateRepeaterRow($(this));
        });
    });


    $(document).on('click', '[data-repeater-delete]', function () {
        let btn = $(this);
        let id = btn.data('id');

        $.ajax({
            url: "{{ route('admin.job-card.session.remove') }}",
            type: "POST",
            data: {
                id: id,
                _token: "{{ csrf_token() }}"
            },
            success: function (res) {
                btn.closest('[data-repeater-item]').remove();
            }
        });
    });


    
        


</script>
@endpush

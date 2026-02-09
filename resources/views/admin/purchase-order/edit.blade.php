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

        </div>
    </div>
</div>
<!-- end page title -->

{!! html()->form('PUT', route('admin.'.request()->segment(2).'.update', $purchase_order->id))->attribute('files', true)->open() !!}

<div class="card border border-success mb-5">
    <div class="card-body">
        <div class="d-flex justify-content-between gap-3">

            <div class="w-100">
                <div class="m-0 form-group{{ $errors->has('from') ? ' has-error' : '' }}">
                    {{ html()->label('From', 'from') }}
                    {{ html()->select('from', App\Models\Party::where('id', $purchase_order->from)->pluck('company_name', 'id'), $purchase_order->from)->id('froms')->placeholder('Choose from')->class('form-control froms') }}
                    <small class="text-danger">{{ $errors->first('from') }}</small>
                </div>
            </div>

            <div class="w-100">
                <div class="m-0 form-group{{ $errors->has('bill_to') ? ' has-error' : '' }}">
                    {{ html()->label('Bill To', 'bill_to') }}
                    {{ html()->select('bill_to', App\Models\Party::where('id', $purchase_order->bill_to)->pluck('company_name', 'id'), $purchase_order->bill_to)->id('billTo')->placeholder('Bill To')->class('form-control billTo') }}
                    <small class="text-danger">{{ $errors->first('bill_to') }}</small>
                </div>
            </div>

            <div class="w-100">
                <div class="m-0 form-group{{ $errors->has('ship_to') ? ' has-error' : '' }}">
                    {{ html()->label('Ship To', 'ship_to') }}
                    {{ html()->select('ship_to', App\Models\Party::where('id', $purchase_order->ship_to)->pluck('company_name', 'id'), $purchase_order->ship_to)->id('shipTo')->placeholder('Ship To')->class('form-control shipTo') }}
                    <small class="text-danger">{{ $errors->first('ship_to') }}</small>
                </div>
            </div>

            <div class="w-100">
                <div class="m-0 form-group{{ $errors->has('consignee') ? ' has-error' : '' }}">
                    {{ html()->label('Consignee', 'consignee') }}
                    {{ html()->select('consignee', App\Models\Party::where('id', $purchase_order->consignee)->pluck('company_name', 'id'), $purchase_order->consignee)->id('consignee')->placeholder('Consignee')->class('form-control consignee') }}
                    <small class="text-danger">{{ $errors->first('consignee') }}</small>
                </div>
            </div>

            <div class="w-75">
                <div class="m-0 form-group{{ $errors->has('po_date') ? ' has-error' : '' }}">
                    {{ html()->label('PO Date', 'po_date') }}
                    {{ html()->text('po_date', $purchase_order->po_date?->format('d F Y'))->id('po_date')->placeholder('PO Date')->class('dateSelector form-control') }}
                    <small class="text-danger">{{ $errors->first('po_date') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>







<div class="report-repeater">
    <div id="kt_docs_repeater_advanced">


        <div data-repeater-list="kt_docs_repeater_advanced">
            @foreach($purchase_order->items as $index => $item)
            <input type="hidden" name="id" value="{{ $item->id }}">
            <div data-repeater-item class="repeater-row row-{{$index}}">
                <div class="card border border-secondary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between gap-2">
                            
                            <div class="w-100">
                                <div class="form-group{{ $errors->has('quality') ? ' has-error' : '' }}">
                                    {{ html()->label('Quality', 'quality') }}
                                    {{ html()->select('quality', App\Models\Quality::orderBy('name', 'asc')->where('id', $item->quality_id)->get()->pluck('name_with_code', 'id'), $item->quality_id)->class('form-control js-choice')->placeholder('Choose Quality') }}
                                    <small class="text-danger">{{ $errors->first('quality') }}</small>
                                </div>
                            </div>

                            <div class="w-50">
                                <div class="form-group{{ $errors->has('gsm') ? ' has-error' : '' }}">
                                    {{ html()->label('GSM', 'gsm') }}
                                    {{ html()->text('gsm', $item->gsm)->class('form-control')->placeholder('GSM') }}
                                    <small class="text-danger">{{ $errors->first('gsm') }}</small>
                                </div>
                            </div>

                            <div class="w-75">
                                <div class="form-group{{ $errors->has('length') ? ' has-error' : '' }}">
                                    {{ html()->label('Length', 'length') }}
                                    <div class="input-group">
                                        {{ html()->text('length', $item->length_cm)->class('form-control')->placeholder('Length') }}
                                        <span class="input-group-text bg-white">CM</span>
                                    </div>
                                    <small class="text-danger">{{ $errors->first('length') }}</small>
                                </div>
                            </div>

                            <div class="w-75">
                                <div class="form-group{{ $errors->has('width') ? ' has-error' : '' }}">
                                    {{ html()->label('Width', 'width') }}
                                    <div class="input-group">
                                        {{ html()->text('width', $item->width_cm)->class('form-control')->placeholder('Width') }}
                                        <span class="input-group-text bg-white">CM</span>
                                    </div>
                                    <small class="text-danger">{{ $errors->first('width') }}</small>
                                </div>
                            </div>

                            <div class="" style="min-width:160px;">
                                <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                                    {{ html()->label('Type', 'type') }}
                                    {{ html()->select('type', ['Reel' => 'Reel', 'Sheet' => 'Sheet'], $item->type)->class('form-control js-choice')->placeholder('Choose Type') }}
                                    <small class="text-danger">{{ $errors->first('type') }}</small>
                                </div>
                            </div>

                            <div class="w-75">
                                <div class="form-group{{ $errors->has('grain') ? ' has-error' : '' }}">
                                    {{ html()->label('Grain', 'grain') }}
                                    {{ html()->select('grain', ['Long' => 'Long', 'Short' => 'Short'], $item->grain)->class('form-control js-choices')->placeholder('Choose Grain') }}
                                    <small class="text-danger">{{ $errors->first('grain') }}</small>
                                </div>
                            </div>

                            

                            

                        </div>





                        <div class="d-flex justify-content-between gap-2">
                            <div class="w-50">
                                <div class="form-group{{ $errors->has('item_number') ? ' has-error' : '' }}">
                                    {{ html()->label('Item Number', 'item_number') }}
                                    {{ html()->text('item_number', $item->item_number)->class('form-control js-choices')->placeholder('Item Number') }}
                                    <small class="text-danger">{{ $errors->first('item_number') }}</small>
                                </div>
                            </div>

                            <div class="w-75">
                                <div class="form-group{{ $errors->has('ream_weight') ? ' has-error' : '' }}">
                                    {{ html()->label('Ream Weight', 'ream_weight') }}
                                    {{ html()->text('ream_weight', $item->ream_weight)->class('form-control')->placeholder('Ream Weight') }}
                                    <small class="text-danger">{{ $errors->first('ream_weight') }}</small>
                                </div>
                            </div>

                            <div class="w-100">
                                <div class="form-group{{ $errors->has('quantity') ? ' has-error' : '' }}">
                                    {{ html()->label('Quantity', 'quantity') }}
                                    <div class="input-group">
                                        {{ html()->text('quantity', $item->quantity)->class('form-control')->placeholder('Quantity') }}
                                        <span class="input-group-text bg-white">MT</span>
                                    </div>
                                    <small class="text-danger">{{ $errors->first('quantity') }}</small>
                                </div>
                            </div>

                            <div class="w-100">
                                <div class="form-group{{ $errors->has('discount') ? ' has-error' : '' }}">
                                    {{ html()->label('Discount', 'discount') }}
                                    {{ html()->text('discount', $item->discount)->class('form-control')->placeholder('Discount') }}
                                    <small class="text-danger">{{ $errors->first('discount') }}</small>
                                </div>
                            </div>

                            <div class="w-100">
                                <div class="form-group{{ $errors->has('remarks') ? ' has-error' : '' }}">
                                    {{ html()->label('Remarks', 'remarks') }}
                                    {{ html()->text('remarks', $item->remarks)->class('form-control')->placeholder('Remarks') }}
                                    <small class="text-danger">{{ $errors->first('remarks') }}</small>
                                </div>
                            </div>

                            <div class="w-100">
                                <div class="m-0 form-group{{ $errors->has('sold_to') ? ' has-error' : '' }}">
                                    {{ html()->label('Sold To', 'sold_to') }}
                                    {{ html()->select('sold_to', App\Models\Party::where('id', $item->sold_to)->pluck('company_name', 'id'), $item->sold_to)->id('soldTo-'.$index)->placeholder('Choose Sold To')->class('form-control soldTo')->attribute('data-kt-repeater', 'select2') }}
                                    <small class="text-danger">{{ $errors->first('sold_to') }}</small>
                                </div>
                            </div>


                            <div style="width:50px;">
                                <div class="text-end">
                                    <button data-repeater-delete type="button" class="btn-labels btn btn-danger" style="margin-top: 23px;">
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

        <div class="d-flex justify-content-end align-items-center mb-3">
            <div class="form-group m-0">
                <button data-repeater-create type="button" class="btn-label btn btn-info text-end btn-sm">
                    <i class="label-icon align-middle fs-16 me-2 bx bx-plus-circle"></i> Add New Row
                </button>
            </div>
        </div>
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

            let index = $row.index();
            $row.find('.soldTo').attr('id', 'soldTo-' + index);

            getParty($row.find('.soldTo'), false, 'Sold To', 'client');

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
        getParty('.froms', false, 'From', 'firm');
        getParty('.billTo', false, 'Bill To', 'client');
        getParty('.shipTo', false, 'Ship To', 'client');
        getParty('.consignee', false, 'Consignee', 'client');
        getParty('.soldTo', false, 'Sold To', 'client');
    });


$(document).on('keyup change', '[data-repeater-item] input', function () {

    const $row = $(this).closest('[data-repeater-item]');

    const length = parseFloat(
        $row.find('input[name$="[length]"]').val()
    ) || 0;

    const width = parseFloat(
        $row.find('input[name$="[width]"]').val()
    ) || 0;

    if (!length || !width) return;

    const grainValue = (width > length) ? 'Long' : 'Short';

    const grainSelect = $row.find('select[name$="[grain]"]')[0];
    console.log

    if (!grainSelect) return;

    grainSelect.value = grainValue;

    if (grainSelect.choicesInstance) {
        grainSelect.choicesInstance.setChoiceByValue(grainValue);
    }
});
</script>
@endpush

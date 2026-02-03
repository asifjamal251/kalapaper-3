<style>
    .is-loading {
        opacity: 0.5;
        pointer-events: none;
        transition: opacity .2s ease;
    }
</style>
{!! html()->form('PUT', route('admin.'.request()->segment(2).'.update',$party->id))
->attribute('enctype','multipart/form-data')
->id('update')
->open() !!}

<input type="hidden" id="old_state" value="{{ $party->state }}">
<input type="hidden" id="old_district" value="{{ $party->district }}">
<input type="hidden" id="old_city" value="{{ $party->city }}">

<div class="card">
    <div class="card-body">
        <div class="row">

            <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                {{ html()->label('Type', 'type') }}  <span class="text-danger">*</span>
                {{ html()->select('type', ['Client' => 'Client', 'Firm' => 'Firm', 'Vendor' => 'Vendor'], $party->type)->class('js-choice form-control')->placeholder('Type') }}
                <small class="text-danger">{{ $errors->first('type') }}</small>
            </div>
        </div>

            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="form-group{{ $errors->has('company_name') ? ' has-error' : '' }}">
                    {{ html()->label('Company Name', 'company_name') }} <span class="star-mandatory text-danger"> *</span>
                    {{ html()->text('company_name', $party->company_name)->class('form-control')->placeholder('Company Name') }}
                    <small class="text-danger">{{ $errors->first('company_name') }}</small>
                </div>
            </div>


            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="form-group{{ $errors->has('gst') ? ' has-error' : '' }}">
                    {{ html()->label('GST', 'gst') }}<span class="star-mandatory text-danger"> *</span>
                    {{ html()->text('gst', $party->gst)->class('form-control')->placeholder('GST') }}
                    <small class="text-danger">{{ $errors->first('gst') }}</small>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="form-group{{ $errors->has('pincode') ? ' has-error' : '' }}">
                    <div class="d-flex justify-content-between">
                     <span>{{ html()->label('Pincode', 'pincode') }} <span class="text-danger">*</span></span>
                     <div class="mt-1 text-muted d-none" id="pincodeLoader">
                        <span class="spinner-border spinner-border-sm me-1"></span>
                        Fetching location details…
                    </div>
                </div>
                {{ html()->text('pincode', $party->pincode)->id('pincode')->class('form-control pincode')->placeholder('Pincode') }}
                <small class="text-danger pincodeError">{{ $errors->first('pincode') }}</small>
            </div>
        </div>

        <!-- State -->
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
                {{ html()->label('State', 'state') }} <span class="text-danger">*</span>
                {{ html()->select('state', [])->class('form-control')->id('state')->placeholder('State')  }}
                <small class="text-danger">{{ $errors->first('state')}}</small>
            </div>
        </div>

        <!-- State -->
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="form-group{{ $errors->has('district') ? ' has-error' : '' }}">
                {{ html()->label('District', 'district') }} <span class="text-danger">*</span>
                {{ html()->select('district', [])->class('form-control')->id('district')->placeholder('District')  }}
                <small class="text-danger">{{ $errors->first('district')}}</small>
            </div>
        </div>

        <!-- City -->
        <div class="col-md-5 col-sm-6 col-xs-12">
            <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                {{ html()->label('City', 'city') }} <span class="text-danger">*</span>
                {{ html()->select('city', [])->class('form-control')->id('city')->placeholder('City') }}
                <small class="text-danger">{{ $errors->first('city') }}</small>
            </div>
        </div>

        <div class="col-md-7 col-sm-12">
            <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                {{ html()->label('Address', 'address') }}  <span class="text-danger">*</span>
                {{ html()->text('address', $party->address)->class('form-control')->placeholder('Address') }}
                <small class="text-danger">{{ $errors->first('address') }}</small>
            </div>
        </div>


        


        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                {{ html()->label('Status')->for('status') }}  <span class="text-danger">*</span>
                {{ html()->select('status', App\Models\Status::whereIn('id', [14, 15])->pluck('name', 'id'), $party->status_id)->class('form-control js-choice')->id('status')->placeholder('Status') }}
                <small class="text-danger">{{ $errors->first('status') }}</small>
            </div>
        </div>



        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="form-group{{ $errors->has('stock_on_email') ? ' has-error' : '' }}">
                {{ html()->label('Stock On Email')->for('stock_on_email') }}  <span class="text-danger">*</span>
                {{ html()->select('stock_on_email', App\Models\Status::whereIn('id', [14, 15])->pluck('name', 'id'), $party->stock_on_email)->class('form-control js-choice')->id('stock_on_email')->placeholder('Stock On Email') }}
                <small class="text-danger">{{ $errors->first('stock_on_email') }}</small>
            </div>
        </div>



        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="form-group{{ $errors->has('login_status') ? ' has-error' : '' }}">
                {{ html()->label('Login Status')->for('login_status') }}  <span class="text-danger">*</span>
                {{ html()->select('login_status', App\Models\Status::whereIn('id', [14, 15])->pluck('name', 'id'), $party->login_status)->class('form-control js-choice')->id('loginStatus')->placeholder('Login Status') }}
                <small class="text-danger">{{ $errors->first('login_status') }}</small>
            </div>
        </div>

        <div class="loginCredentials col-md-4 col-sm-6 col-xs-12">
            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                {{ html()->label('Username', 'username') }}
                {{ html()->text('username', $party->username)->class('form-control')->placeholder('Username')->attribute('autocomplete', 'off') }}
                <small class="text-danger">{{ $errors->first('username') }}</small>
            </div>
        </div>

        <div class="loginCredentials col-md-4 col-sm-6 col-xs-12">
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                {{ html()->label('Password', 'password') }}
                {{ html()->text('password', $party->plain_password)->class('form-control')->placeholder('Password')->attribute('autocomplete', 'off') }}
                <small class="text-danger">{{ $errors->first('password') }}</small>
            </div>
        </div>




    </div>
</div>
</div>




<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="report-repeater">
        <div id="kt_docs_repeater_advanced_email">

            <div data-repeater-list="kt_docs_repeater_advanced_email">
                @forelse(
                    old(
                        'kt_docs_repeater_advanced_email',
                        collect($party->email ?? [])->map(fn ($e) => ['email' => $e])->toArray()
                    ) as $index => $item
                )

                <div data-repeater-item class="repeater-row row-{{$index}}">

                    <div class="gap-2 d-flex justify-content-between flex-sm-wrape">

                        <div class="w-100 form-group{{ $errors->has("kt_docs_repeater_advanced_email.$index.email") ? ' has-error' : '' }}">
                            {{ html()->label('Email', "kt_docs_repeater_advanced_email[$index][email]") }} 

                            {{ html()->email("kt_docs_repeater_advanced_email[$index][email]")
                                ->class('form-control numOnly')
                                ->placeholder('Email')
                                ->value($item['email'] ?? '') }}

                            <small class="text-danger">
                                {{ $errors->first("kt_docs_repeater_advanced_email.$index.email") }}
                            </small>
                        </div>

                        <div class="m-0 form-group remove-item" style="width:44px;">
                            <div class="text-end">
                                <button data-repeater-delete type="button"
                                    class="btn btn-danger btn-sm fs-18"
                                    style="margin-top:20px;height:38px;min-width:40px;width:40px;">
                                    -
                                </button>
@if($loop->index == 0)
                                <button data-repeater-create type="button"
                                    class="btn btn-success btn-sm add fs-18"
                                    style="margin-top:20px;height:38px;min-width:40px;width:40px;">
                                    +
                                </button>
                                @endif
                            </div>
                        </div>

                    </div>

                </div>
                @empty

                            <div data-repeater-item class="repeater-row row-{{$index}}">

                                <div class="gap-2 d-flex justify-content-between flex-sm-wrape">

                                    <div class="w-100 form-group{{ $errors->has("kt_docs_repeater_advanced_email.$index.email") ? ' has-error' : '' }}">
                                        {{ html()->label('Email', "kt_docs_repeater_advanced_email[$index][email]") }} 

                                        {{ html()->email("kt_docs_repeater_advanced_email[$index][email]")->class('form-control numOnly')->placeholder('Email') }}

                                        <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced_email.$index.email") }}</small>
                                    </div>


                                    <div class="m-0 form-group remove-item" style="width:44px;">
                                        <div class="text-end">
                                            <button data-repeater-delete type="button" type="button" class="btn btn-danger btn-sm fs-18" style="margin-top:20px;height:38px;min-width:40px;width:40px;">
                                                -
                                            </button>

                                            <button data-repeater-create type="button" type="button" class="btn btn-success btn-sm  add fs-18" style="margin-top:20px;height:38px;min-width:40px;width:40px;">
                                                +
                                            </button>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            
                @endforelse
            </div>

        </div>
    </div>

            </div>
        </div>
    </div>

    <div class="col-md-6 col-sm-12">

       <div class="card">
        <div class="card-body">
            <div class="report-repeater">
        <div id="kt_docs_repeater_advanced_contact_no">

            <div data-repeater-list="kt_docs_repeater_advanced_contact_no">
                @forelse(
                    old(
                        'kt_docs_repeater_advanced_contact_no',
                        collect($client->contact_no ?? [])->map(fn ($c) => ['contact_no' => $c])->toArray()
                    ) as $index => $item
                )

                <div data-repeater-item class="repeater-row row-{{$index}}">

                    <div class="gap-2 d-flex justify-content-between flex-sm-wrape">

                        <div class="w-100 form-group{{ $errors->has("kt_docs_repeater_advanced_contact_no.$index.contact_no") ? ' has-error' : '' }}">
                            {{ html()->label('Contact No.', "kt_docs_repeater_advanced_contact_no[$index][contact_no]") }} 

                            {{ html()->text("kt_docs_repeater_advanced_contact_no[$index][contact_no]")
                                ->class('form-control numOnly')
                                ->placeholder('Contact No.')
                                ->value($item['contact_no'] ?? '') }}

                            <small class="text-danger">
                                {{ $errors->first("kt_docs_repeater_advanced_contact_no.$index.contact_no") }}
                            </small>
                        </div>

                        <div class="m-0 form-group remove-item" style="width:44px;">
                            <div class="text-end">
                           
                                <button data-repeater-delete type="button"
                                    class="btn btn-danger btn-sm fs-18"
                                    style="margin-top:20px;height:38px;min-width:40px;width:40px;">
                                    -
                                </button>
@if($loop->index == 0)
                                <button data-repeater-create type="button"
                                    class="btn btn-success btn-sm add fs-18"
                                    style="margin-top:20px;height:38px;min-width:40px;width:40px;">
                                    +
                                </button>
                                @endif
                            </div>
                        </div>

                    </div>

                </div>

                @empty



                        <div data-repeater-item class="repeater-row row-{{$index}}">

                            <div class="gap-2 d-flex justify-content-between flex-sm-wrape">

                                <div class="w-100 form-group{{ $errors->has("kt_docs_repeater_advanced_contact_no.$index.contact_no") ? ' has-error' : '' }}">
                                    {{ html()->label('Contact No.', "kt_docs_repeater_advanced_contact_no[$index][contact_no]") }} 

                                    {{ html()->text("kt_docs_repeater_advanced_contact_no[$index][contact_no]")->class('form-control numOnly')->placeholder('Contact No.') }}

                                    <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced_contact_no.$index.contact_no") }}</small>
                                </div>


                                <div class="m-0 form-group remove-item" style="width:44px;">
                                    <div class="text-end">
                                        <button data-repeater-delete type="button" type="button" class="btn btn-danger btn-sm fs-18" style="margin-top:20px;height:38px;min-width:40px;width:40px;">
                                            -
                                        </button>

                                        <button data-repeater-create type="button" type="button" class="btn btn-success btn-sm  add fs-18" style="margin-top:20px;height:38px;min-width:40px;width:40px;">
                                            +
                                        </button>
                                    </div>
                                </div>

                            </div>

                        </div>

                @endforelse
            </div>

        </div>
    </div>

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

<script type="text/javascript" src="{{asset('assets/js/pages/form-repeater.js')}}"></script>
<script type="text/javascript">

 var rowCounter = 0;

 $('#kt_docs_repeater_advanced_contact_no').repeater({
    isFirstItemUndeletable: true,
    show: function () {
        var $row = $(this);
        $row.addClass('row-' + rowCounter);
        rowCounter++;

        $row.find('small.text-danger').html('');
        $row.find('.form-group').removeClass('has-error');
        $row.find('.add').hide();

        $row.slideDown('fast', function () {
            //$row.find('input[name*="[item]"]').first().focus().addClass('ojkk');
        });
    },

    hide: function (deleteElement) {
        $(this).slideUp(deleteElement);
    }
});


 $('#kt_docs_repeater_advanced_email').repeater({
    isFirstItemUndeletable: true,
    show: function () {
        var $row = $(this);
        $row.addClass('row-' + rowCounter);
        rowCounter++;

        $row.find('small.text-danger').html('');
        $row.find('.form-group').removeClass('has-error');
        $row.find('.add').hide();

        $row.slideDown('fast', function () {
            //$row.find('input[name*="[item]"]').first().focus().addClass('ojkk');
        });
    },

    hide: function (deleteElement) {
        $(this).slideUp(deleteElement);
    }
});
</script>
<script>

    function startPincodeLoading() {
        $('#pincodeLoader').removeClass('d-none');

        $('#state, #district, #city')
        .addClass('is-loading')
        .prop('disabled', true);
    }

    function stopPincodeLoading() {
        $('#pincodeLoader').addClass('d-none');

        $('#state, #district, #city')
        .removeClass('is-loading')
        .prop('disabled', false);
    }

    let choiceInstances = {}; // store all Choice objects

    function initChoice(selector) {
        let el = document.querySelector(selector);
        if (!el) return;

        if (choiceInstances[selector]) {
            try { choiceInstances[selector].destroy(); } catch (e) {}
        }

        choiceInstances[selector] = new Choices(el, {
            searchEnabled: true,
            searchChoices: true,
            placeholder: true,
            searchPlaceholderValue: "Search or type...",

        // allow user typing
            addItems: true,
        addChoices: true,      // 🟢 IMPORTANT
        allowHTML: true,
        allowHtmlUserInput: true,

        // allow manual text to become a choice
        addItemFilter: (value) => !!value && value.trim() !== "",
        duplicateItemsAllowed: false,
        removeItemButton: true,

        noResultsText: "No results found. Press Enter to add.",
        noChoicesText: "Type to add.",
        itemSelectText: "Press Enter to select",

        callbackOnInit: function () {
            let instance = this;

            // 🟢 When user types text → add it as a new choice
            instance.input.element.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    let value = instance.input.element.value.trim();
                    if (value !== "" && !instance._findChoiceByValue(value)) {
                        instance.setChoices([{
                            value: value,
                            label: value,
                            selected: true,
                            customProperties: { manual: true }
                        }], 'value', 'label', true);
                    }
                }
            });
        }
    });
    }

    function updateSelect(selector, items, selected = null) {
    let select = $(selector);
    select.empty();

    items.forEach(item => {
        let isSelected = selected && item.value == selected ? 'selected' : '';
        select.append(`<option value="${item.value}" ${isSelected}>${item.text}</option>`);
    });
}

    function loadByPincode(pincode, isEdit = false) {
    if (pincode.length !== 6) return;

    startPincodeLoading();

    $.get("/admin/common/ajax/pincode/" + pincode, function (res) {
        stopPincodeLoading();

        if (!res.success) return;

        let oldState    = $('#old_state').val();
        let oldDistrict = $('#old_district').val();
        let oldCity     = $('#old_city').val();

        updateSelect('#state', [
            { value: res.state, text: res.state }
        ], isEdit ? oldState : null);

        updateSelect('#district', [
            { value: res.district, text: res.district }
        ], isEdit ? oldDistrict : null);

        let cities = [{ value: '', text: 'Select City' }];
        res.cities.forEach(c => {
            res.names.forEach(n => {
                let txt = `${c} – ${n}`;
                cities.push({ value: txt, text: txt });
            });
        });

        updateSelect('#city', cities, isEdit ? oldCity : null);

        initChoice('#state');
        initChoice('#district');
        initChoice('#city');
    });
}

$(document).ready(function () {
    let pincode = $('#pincode').val();
    if (pincode) {
        loadByPincode(pincode, true); // 🔥 EDIT MODE
    }
});

$('.pincode').on('keyup', function () {
    let pincode = $(this).val();
    if (pincode.length === 6) {
        loadByPincode(pincode, false); // 🔥 CHANGE MODE
    }
});


    $(document).ready(function () {

        function toggleLoginFields(value) {
            if (parseInt(value) === 14) {
                $('.loginCredentials').stop(true, true).slideDown(300);
            } else {
                $('.loginCredentials').stop(true, true).slideUp(300);
            }
        }

        toggleLoginFields($('#loginStatus').val());

        $('#loginStatus').on('change', function () {
            toggleLoginFields(this.value);
        });

    });
</script>    


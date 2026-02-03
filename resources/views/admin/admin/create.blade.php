{{ html()->form('POST', route('admin.' . request()->segment(2) . '.store'))->attribute('enctype', 'multipart/form-data')->id('store')->open() }}

<div class="card">
    <div class="card-body">
        <div class="row">

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    {{ html()->label('Name', 'name') }}
                    {{ html()->text('name')->class('form-control')->placeholder('Name') }}
                    <small class="text-danger">{{ $errors->first('name') }}</small>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    {{ html()->label('Email', 'email') }}
                    {{ html()->email('email')->class('form-control')->placeholder('eg: foo@bar.com') }}
                    <small class="text-danger">{{ $errors->first('email') }}</small>
                </div>
            </div>


            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                    {{ html()->label('Username', 'username') }}
                    {{ html()->text('username')->class('form-control')->placeholder('Username') }}
                    <small class="text-danger">{{ $errors->first('username') }}</small>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
                    {{ html()->label('Role', 'role') }}
                    {{ html()->select('role', App\Models\Role::whereNotIn('id', [1])->get()->pluck('role_name', 'id'))->class('form-control js-choice')->placeholder('Choose Roll') }}
                    <small class="text-danger">{{ $errors->first('role') }}</small>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    {{ html()->label('Password', 'password') }}
                    {{ html()->text('password')->class('form-control')->placeholder('Password')->attribute('autocomplete', 'new-password') }}
                    <small class="text-danger">{{ $errors->first('password') }}</small>
                </div>
            </div>

            

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                    {{ html()->label('Status')->for('status') }}
                    {{ html()->select('status', App\Models\Status::whereIn('id', [14, 15])->pluck('name', 'id'), 14)->class('form-control js-choice')->id('status')->placeholder('Status') }}
                    <small class="text-danger">{{ $errors->first('status') }}</small>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('enabled_2fa') ? ' has-error' : '' }}">
                    {{ html()->label('2FA Enabled')->for('enabled_2fa') }}
                    {{ html()->select('enabled_2fa', App\Models\Status::whereIn('id', [14, 15])->pluck('name', 'id'), 15)->class('form-control js-choice')->id('enabled_2fa')->placeholder('2FA Enabled') }}
                    <small class="text-danger">{{ $errors->first('enabled_2fa') }}</small>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('login_time_restriction_enabled') ? ' has-error' : '' }}">
                    {{ html()->label('Login Time Restriction Enabled')->for('login_time_restriction_enabled') }}
                    {{ html()->select('login_time_restriction_enabled', App\Models\Status::whereIn('id', [14, 15])->pluck('name', 'id'), 15)->class('form-control js-choice')->id('login_time_restriction_enabled')->placeholder('Login Time Restriction Enabled') }}
                    <small class="text-danger">{{ $errors->first('login_time_restriction_enabled') }}</small>
                </div>
            </div>

            <div class="col-md-6 col-sm-12 d-flex gap-3">

                <div class="w-50 form-group{{ $errors->has('login_allowed_from') ? ' has-error' : '' }}">
                    {{ html()->label('Login Allowed From', 'login_allowed_from') }}
                    {{ html()->text('login_allowed_from')->class('form-control timeInput')->placeholder('Login Allowed From') }}
                    <small class="text-danger">{{ $errors->first('login_allowed_from') }}</small>
                </div>

                <div class="w-50 form-group{{ $errors->has('login_allowed_to') ? ' has-error' : '' }}">
                    {{ html()->label('Login Allowed To', 'login_allowed_to') }}
                    {{ html()->text('login_allowed_to')->class('form-control timeInput')->placeholder('Login Allowed To') }}
                    <small class="text-danger">{{ $errors->first('login_allowed_to') }}</small>
                </div>
            </div>


            <div class="col-md-6 col-sm-12">
                <div class="form-group{{ $errors->has('ip_enabled') ? ' has-error' : '' }}">
                    {{ html()->label('IP Enabled')->for('ip_enabled') }}
                    {{ html()->select('ip_enabled', App\Models\Status::whereIn('id', [14, 15])->pluck('name', 'id'), 15)->class('form-control js-choice')->id('ip_enabled')->placeholder('IP Enabled') }}
                    <small class="text-danger">{{ $errors->first('ip_enabled') }}</small>
                </div>
            </div>


   

<div class="report-repeater">
    <div id="kt_docs_repeater_advanced">

        <div data-repeater-list="kt_docs_repeater_advanced">
            @foreach(old('kt_docs_repeater_advanced', [[]]) as $index => $item)

            <div data-repeater-item class="repeater-row row-{{$index}}">

                        <div class="gap-4 d-flex justify-content-between flex-sm-wrape">

                            <div class="w-100 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.ips") ? ' has-error' : '' }}">
                                {{ html()->label('Allowed IP Addresses', "kt_docs_repeater_advanced[$index][ips]") }} 

                                {{ html()->text("kt_docs_repeater_advanced[$index][ips]")->class('form-control numOnly')->placeholder('e.g. 192.168.1.1') }}

                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.ips") }}</small>
                            </div>

                            <div class="w-100 form-group{{ $errors->has("kt_docs_repeater_advanced.$index.notes") ? ' has-error' : '' }}">
                                {{ html()->label('Notes', "kt_docs_repeater_advanced[$index][notes]") }} 
                                {{ html()->text("kt_docs_repeater_advanced[$index][notes]")->class('form-control')->placeholder('Office Airtel') }}
                                <small class="text-danger">{{ $errors->first("kt_docs_repeater_advanced.$index.notes") }}</small>
                            </div>

                            <div class="m-0 form-group remove-item" style="width:44px;">
                                <div class="text-end">
                                    <button data-repeater-delete type="button" type="button" class="btn btn-danger btn-sm  remove-ip fs-18" style="margin-top:18px;height:38px;min-width:40px;width:40px;">
                                        -
                                    </button>

                                    <button data-repeater-create type="button" type="button" class="btn btn-success btn-sm  add-ip fs-18" style="margin-top:18px;height:38px;min-width:40px;width:40px;">
                                        +
                                    </button>
                                </div>
                            </div>

                        </div>

            </div>
            @endforeach
        </div>
    </div>
</div>




     </div>
    </div>
</div>




<div class="col-md-12">
    <div class="mt-2 form-group">
        {{ html()->button('Save Details')->type('button')->class('btn btn-success bg-gradient')->attribute('onclick = store(this)') }}
    </div>
</div>
{{ html()->form()->close() }}


<script type="text/javascript" src="{{asset('assets/js/pages/form-repeater.js')}}"></script>
<script type="text/javascript">

 var rowCounter = 0;

 $('#kt_docs_repeater_advanced').repeater({
    isFirstItemUndeletable: true,
    show: function () {
        var $row = $(this);
        $row.addClass('row-' + rowCounter);
        rowCounter++;

        $row.find('small.text-danger').html('');
        $row.find('.form-group').removeClass('has-error');
        $row.find('.add-ip').hide();

        $row.slideDown('fast', function () {
            //$row.find('input[name*="[item]"]').first().focus().addClass('ojkk');
        });
    },

    hide: function (deleteElement) {
        $(this).slideUp(deleteElement);
    }
});
</script>

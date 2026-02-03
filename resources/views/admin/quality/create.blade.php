{{ html()->form('POST', route('admin.' . request()->segment(2) . '.store'))->attribute('enctype', 'multipart/form-data')->id('store')->open() }}

<div class="card">
    <div class="card-body">
        <div class="row">

            <div class="col-md-12 col-sm-12">
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    {{ html()->label('Name', 'name') }}
                    {{ html()->text('name')->class('form-control')->placeholder('Name') }}
                    <small class="text-danger">{{ $errors->first('name') }}</small>
                </div>
            </div>

            <div class="col-md-12 col-sm-12">
                <div class="m-0 form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                    {{ html()->label('Code', 'code') }}
                    {{ html()->text('code')->class('form-control')->placeholder('Code') }}
                    <small class="text-danger">{{ $errors->first('code') }}</small>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="mt-4 form-group">
    {{ html()->button('Save Details')->type('button')->class('btn btn-success bg-gradient')->attribute('onclick = store(this)') }}
</div>

{{ html()->form()->close() }}

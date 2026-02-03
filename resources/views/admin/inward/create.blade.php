
{{ html()->form('POST', route('admin.' . request()->segment(2) . '.store'))->attribute('enctype', 'multipart/form-data')->id('store')->open() }}

<div class="card">
    <div class="card-body">
        <div class="row">

            <div class="col-md-12 col-sm-12">
                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                    {{ html()->label('Status')->for('status') }}  <span class="text-danger">*</span>
                    {{ html()->select('status', App\Models\Status::whereIn('id', [20, 21])->pluck('name', 'id'), 21)->class('form-control js-choice')->id('status')->placeholder('Status') }}
                    <small class="text-danger">{{ $errors->first('status') }}</small>
                </div>
            </div>


            <div class="col-md-12 col-sm-12">
                <div class="form-group{{ $errors->has('file') ? ' has-error' : '' }}">
                    {{ html()->label('File', 'file') }} <span class="text-danger">*</span>
                    {{ html()->file('file')->class('form-control')->id('file') }}
                    <small class="text-danger">{{ $errors->first('file')}}</small>
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


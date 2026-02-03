@php
    $stock  = App\models\DailyStock::today();
@endphp

@extends('admin.layouts.app')


@section('main')

<div class="row h-100">
    <div class="col-lg-3 col-md-6">
        <div class="card card-success">
            <div class="card-header">
                <h6 class="card-title mb-0">Available Stock</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h4 class=" mb-0"><span class="counter-value text-white">{{$stock->available_stock}}</span></h4>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div>
@endsection

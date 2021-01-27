@extends('layouts.app')

@section('page-title', __('sale-stages'))
@section('page-heading', $edit ? $saleStage->name : __('Thêm trạng thái sale'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('sale-stages.index') }}">@lang('sale-stages')</a>
    </li>
    <li class="breadcrumb-item active">
        {{ __($edit ? 'Sửa' : 'Tạo') }}
    </li>
@stop

@section('content')

@include('partials.messages')

@if ($edit)
    {!! Form::open(['route' => ['sale-stages.update', $saleStage], 'method' => 'PUT', 'id' => 'sale-stage-form']) !!}
@else
    {!! Form::open(['route' => 'sale-stages.store', 'id' => 'sale-stage-form']) !!}
@endif

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <h5 class="card-title">
                    @lang('SaleStage Details')
                </h5>
                <p class="text-muted">
                    @lang('Thông tin trạng thái sale.')
                </p>
            </div>
            <div class="col-md-9">
                <div class="form-group">
                    <label for="name">@lang('Tên')</label>
                    <input type="text"
                           class="form-control input-solid"
                           id="name"
                           name="name"
                           placeholder="@lang('SaleStage Name')"
                           value="{{ $edit ? $saleStage->name : old('name') }}">
                </div>
                 <div class="form-group">
                    <label for="name">@lang('Mã')</label>
                    <input type="text"
                           class="form-control input-solid"
                           id="code"
                           name="code"
                           placeholder="@lang('Mã')"
                           value="{{ $edit ? $saleStage->code : old('code') }}">
                </div>
                <div class="form-group">
                    <label for="address">@lang('Miêu tả')</label>
                    <textarea name="description" id="description" rows="5"
                              class="form-control input-solid">{{ $edit ? $saleStage->description : '' }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-primary">
    {{ __($edit ? 'Cập nhật trạng thái sale' : 'Tạo trạng thái sale') }}
</button>

@stop

@section('scripts')
    @if ($edit)
        {!! JsValidator::formRequest('ThaoHR\Http\Requests\SaleStage\UpdateSaleStageRequest', '#sale-stage-form') !!}
    @else
        {!! JsValidator::formRequest('ThaoHR\Http\Requests\SaleStage\CreateSaleStageRequest', '#sale-stage-form') !!}
    @endif
@stop

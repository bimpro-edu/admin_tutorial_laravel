@extends('layouts.app')

@section('page-title', __('Chiến dịch'))
@section('page-heading', $edit ? $campaign->name : __('Tạo mới chiến dịch'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('campaigns.index') }}">@lang('Chiến dịch')</a>
    </li>
    <li class="breadcrumb-item active">
        {{ __($edit ? 'Sửa' : 'Tạo') }}
    </li>
@stop

@section('content')

@include('partials.messages')

@if ($edit)
    {!! Form::open(['route' => ['campaigns.update', $campaign], 'method' => 'PUT', 'id' => 'campaign-form']) !!}
@else
    {!! Form::open(['route' => 'campaigns.store', 'id' => 'campaign-form']) !!}
@endif

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <h5 class="card-title">
                    @lang('Chi tiết chiến dịch')
                </h5>
                <p class="text-muted">
                    @lang('Thông tin chiến dịch')
                </p>
            </div>
            <div class="col-md-9">
                <div class="form-group">
                    <label for="name">@lang('Tên')</label>
                    <input type="text"
                           class="form-control input-solid"
                           id="name"
                           name="name"
                           placeholder="@lang('Tên chiến dịch')"
                           value="{{ $edit ? $campaign->name : old('name') }}">
                </div>
                 <div class="form-group">
                    <label for="name">@lang('Mã')</label>
                    <input type="text"
                           class="form-control input-solid"
                           id="code"
                           name="code"
                           placeholder="@lang('Mã')"
                           value="{{ $edit ? $campaign->code : old('code') }}">
                </div>
                <div class="form-group">
                    <label for="address">@lang('Description')</label>
                    <textarea name="description" id="description" rows="5"
                              class="form-control input-solid">{{ $edit ? $campaign->description : '' }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-primary">
    {{ __($edit ? 'Cập nhật' : 'Tạo chiến dịch') }}
</button>

@stop

@section('scripts')
    @if ($edit)
        {!! JsValidator::formRequest('ThaoHR\Http\Requests\Campaign\UpdateCampaignRequest', '#campaign-form') !!}
    @else
        {!! JsValidator::formRequest('ThaoHR\Http\Requests\Campaign\CreateCampaignRequest', '#campaign-form') !!}
    @endif
@stop

@extends('layouts.app')

@section('page-title', __('clients'))
@section('page-heading', $edit ? $client->name : __('Create New Client'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('clients.index') }}">@lang('clients')</a>
    </li>
    <li class="breadcrumb-item active">
        {{ __($edit ? 'Edit' : 'Create') }}
    </li>
@stop

@section('content')

@include('partials.messages')

@if ($edit)
    {!! Form::open(['route' => ['clients.update', $client], 'method' => 'PUT', 'id' => 'client-form']) !!}
@else
    {!! Form::open(['route' => 'clients.store', 'id' => 'client-form']) !!}
@endif

<div class="card">
    <div class="card-body">
        <ul class="nav nav-tabs" id="nav-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active"
                   id="details-tab"
                   data-toggle="tab"
                   href="#client-info"
                   role="tab"
                   aria-controls="home"
                   aria-selected="true">
                    @lang('Thông tin khách hàng')
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link"
                   id="authentication-tab"
                   data-toggle="tab"
                   href="#client-histories"
                   role="tab"
                   aria-controls="home"
                   aria-selected="true">
                    @lang('Lịch sử')
                </a>
            </li>
        </ul>
        <div class="tab-content mt-4" id="nav-tabContent">
           <div class="tab-pane fade show active px-2"
                         id="client-info"
                         role="tabpanel"
                         aria-labelledby="nav-home-tab">
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-group">
                            <label for="name">@lang('Họ Tên')</label>
                            <input type="text"
                                   class="form-control input-solid"
                                   id="name"
                                   name="name"
                                   placeholder="@lang('Client Name')"
                                   value="{{ $edit ? $client->name : old('name') }}">
                        </div>
                         <div class="form-group">
                            <label for="name">@lang('Mã khách hàng')</label>
                            <input type="text"
                                   class="form-control input-solid"
                                   id="code"
                                   name="code"
                                   placeholder="@lang('Code')"
                                   value="{{ $edit ? $client->code : ($code?  $code : old('code')) }}">
                        </div>
                        <div class="form-group">
                            <label for="name">@lang('CMND/Hộ chiếu')</label>
                            <input type="text"
                                   class="form-control input-solid"
                                   id="national_id"
                                   name="national_id"
                                   placeholder="@lang('National ID')"
                                   value="{{ $edit ? $client->national_id : old('national_id') }}">
                        </div>
                         <div class="form-group">
                            <label for="name">@lang('Số điện thoại')</label>
                            <input type="text"
                                   class="form-control input-solid"
                                   id="phone"
                                   name="phone"
                                   placeholder="@lang('phone')"
                                   value="{{ $edit ? $client->phone : old('phone') }}">
                        </div>  
                         <div class="form-group">
                            <label for="name">@lang('Đại chỉ')</label>
                            <input type="text"
                                   class="form-control input-solid"
                                   id="address"
                                   name="address"
                                   placeholder="@lang('address')"
                                   value="{{ $edit ? $client->address : old('address') }}">
                        </div> 
                         <div class="form-group">
                            <label for="name">@lang('Số tiền giải ngân')</label>
                            <input type="text"
                                   class="form-control input-solid"
                                   id="disbursement_amount"
                                   name="disbursement_amount"
                                   placeholder="@lang('Disbursement Amount')"
                                   value="{{ $edit ? number_format($client->disbursement_amount) : old('disbursement_amount') }}">
                        </div>
                        <div class="form-group">
                            <label for="name">@lang('Phân cho')</label>
                            {!! Form::select('assign_user_id', $users, $edit ? $client->assign_user_id : $currentUser->id, ['class' => 'form-control form-control-chosen', 'id' => 'assign_user_id']) !!}
                        </div>
                         <div class="form-group">
                            <label for="name">@lang('Chiến dịch')</label>
                            {!! Form::select('campaign_id', $campaigns, $edit ? $client->campaign_id : '', ['class' => 'form-control form-control-chosen', 'id' => 'campaign_id']) !!}
                        </div>
                        <div class="form-group">
                            <label for="name">@lang('Ngày nhập')</label>
                            <input type="text"
                                   class="form-control input-solid"
                                   id="import_date"
                                   name="import_date"
                                   placeholder="@lang('Import Date')"
                                   value="{{ $edit ? date('d-m-Y', strtotime($client->import_date)) : old('import_date') }}">
                        </div> 
                        <div class="form-group">
                            <label for="name">@lang('Sale stage')</label>
                            {!! Form::select('sale_stage_id', $saleStages, $edit ? $client->sale_stage_id : '', ['class' => 'form-control form-control-chosen', 'id' => 'sale_stage_id']) !!}
                        </div>  
                        <div class="form-group">
                            <label for="money_limit">@lang('Hạn mức')</label>
                            <input type="text"
                                   class="form-control input-solid"
                                   id="money_limit"
                                   name="money_limit"
                                   placeholder="@lang('Money limit')"
                                   value="{{ $edit ? number_format($client->money_limit) : old('money_limit') }}">
                        </div>                                                                                        
                        <div class="form-group">
                            <label for="address">@lang('Miêu tả')</label>
                            <textarea name="description" id="description" name="description" rows="5"
                                      class="form-control input-solid">{{ $edit ? $client->description : '' }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="name">@lang('Trạng thái')</label>
                            {!! Form::select('status', $statuses, $edit ? $client->status : '', ['class' => 'form-control form-control-chosen', 'id' => 'status']) !!}
                        </div> 
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    {{ __($edit ? 'Cập nhật' : 'Tạo khách hàng') }}
                </button>
            </div>
            @if (!empty($clientHistories))
           <div class="tab-pane fade px-2"
             id="client-histories"
             role="tabpanel"
             aria-labelledby="nav-home-tab">
             	@include('client.partials.client-histories')
             </div>
             
             @endif
        </div>
    </div>
</div>

@stop

@section('scripts')
	{!! HTML::script('ckeditor/ckeditor.js') !!}
	{!! HTML::script('assets/js/as/client.js') !!}
    @if ($edit)
        {!! JsValidator::formRequest('ThaoHR\Http\Requests\Client\UpdateClientRequest', '#client-form') !!}
    @else
        {!! JsValidator::formRequest('ThaoHR\Http\Requests\Client\CreateClientRequest', '#client-form') !!}
    @endif
@stop

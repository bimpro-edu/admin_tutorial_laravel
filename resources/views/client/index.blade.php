@extends('layouts.app')

@section('page-title', __('Khách hàng'))
@section('page-heading', __('Khách hàng'))

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">
        @lang('CRM')
    </li>
    <li class="breadcrumb-item active">
        @lang('Danh sách khách hàng')
    </li>
@stop

@section('content')

    @include('partials.messages')
    @if (session()->has('duplicate_results'))
        <div class="alert alert-success">
        	<h1>{{session()->get('duplicate_results')['message']}}, Danh sách trùng:</h1>
        	@foreach (session()->get('duplicate_results')['duplicate_clients'] as $line => $client)
        	Dòng {{$line + 1}}, Họ tên:{{$client->name}}, Phone: {{$client->phone}}<br/>
        	@endforeach
    	</div>
    @endif
    <div class="card">
      <div class="card-body">
<div class="row">
        		
				<div class="col-md-4">
            	{!! Form::open(['route' => 'clients.assign-client', 'id' => 'reassign-client-form', 'class' => 'pb-2 mb-3 border-bottom-ligh']) !!}
            	<input type="hidden" id="client_ids" value="">
            	<div class="row">               	
        			<div class="col-md-7">
                         {!! Form::select('reassign_user_id', $users, null, ['class' => 'form-control form-control-chosen', 'id' => 'reassign_user_id']) !!}
                	</div>
                	<div class="col-md-5">
                    	<button type="submit" class="btn btn-primary btn-rounded"><i class="fa fa-user mr-2" aria-hidden="true"></i>&nbsp;@lang('Gán cho')</button>
                	</div>
            	</div>
            	{{ Form::close() }}
            	</div>        	
            	<div class="col-md-4">
                	
                	{!! Form::open(['route' => 'clients.export-client', 'id' => 'export-client-form', 'class' => 'pb-2 mb-3 border-bottom-ligh']) !!}
                       <div class="row"> 
                           <div class="col-md-7">
                            {!! Form::select('report_type', $reportTypes, null, ['class' => 'form-control form-control-chosen', 'id' => 'report_type']) !!}
                           </div>
                           <div class="col-md-5"> 
                            <button type="submit" class="btn btn-primary btn-rounded"><i class="fa fa-download mr-2" aria-hidden="true"></i>&nbsp;@lang('Xuất exel')</button>
                           </div>
                       </div>
                    {{ Form::close() }}
                    
            	</div>
    	       <div class="col-md-4">
    	        <a href="{{ route('clients.import') }}" class="btn btn-primary btn-rounded">
                        <i class="fa fa-upload mr-2" aria-hidden="true"></i>
                        @lang('Nhập exel')
                </a>
    	        <a href="#" class="btn btn-primary btn-rounded btn-danger btnDelete">
                        <i class="fas fa-trash mr-2"></i>
                        @lang('Xoá')
                </a>                
                <a href="{{ route('clients.create') }}" class="btn btn-primary btn-rounded">
                    <i class="fas fa-plus mr-2"></i>@lang('Thêm mới')
                </a>
                </div>
        	</div>        
      </div>
    </div>
    <div class="card">
        <div class="card-body">
                <form action="" id="search_form" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="input-group custom-search-form">
                                <input type="text"
                                       class="form-control input-solid"
                                       name="search"
                                       value="{{ Request::get('search') }}"
                                       placeholder="@lang('Tìm kiếm...')">
                            </div>
                        </div>
    
                        <div class="col-md-3">
                             <div class="form-group custom-search-form">
                                {!! Form::select('sale_stage_id', $saleStages, null, ['class' => 'form-control form-control-chosen', 'id' => 'sale_stage_id']) !!}
                            </div> 
                       </div>
                        <div class="col-md-3">
                             <div class="form-group custom-search-form">
                                {!! Form::select('assign_user_id', $users, null, ['class' => 'form-control form-control-chosen', 'id' => 'assign_user_id']) !!}
                             </div>
                        </div>
                        <div class="col-md-3">
                         <div class="form-group custom-search-form">
                            {!! Form::select('range_time', $rangeDates, null, ['class' => 'form-control form-control-chosen', 'id' => 'range_time']) !!}
                         </div>
                    	</div> 
                    	<div class="col-md-3">
                             <div class="form-group custom-search-form">
                                {!! Form::select('campaign_id', $campaigns, null, ['class' => 'form-control form-control-chosen', 'id' => 'campaign_id']) !!}
                             </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group custom-search-form">
                                    <input type="text"
                                           class="form-control input-solid"
                                           id="from_date"
                                           name="from_date"
                                           placeholder="@lang('Từ ngày')">
             
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group custom-search-form">
                                <input type="text"
                                       class="form-control input-solid"
                                       id="to_date"
                                       name="to_date"
                                       placeholder="@lang('Đến ngày')">
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                         <select id="limit" name="limit" class="form-control form-control-chosen">
                            	<option value="">Số bản ghi trên trang:</option>
                            	<option value="20">20</option>
                            	<option value="50">50</option>
                            	<option value="100">100</option>
                            	<option value="200">200</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary btn-rounded float-left" type="submit" id="search-users-btn">
                                @lang('Tìm kiếm')&nbsp;
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>        
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive" id="users-table-wrapper">
                <table class="table table-striped table-borderless">
                    <thead>
                    <tr>
                    	<th class="min-width-50"><input type="checkbox" id="select_all" class="select_all"></th>
                    	<th class="min-width-100">@lang('Ngày nhập')</th>
                        <th class="min-width-100">@lang('Họ tên')</th>
                        <th class="min-width-100">@lang('Chiến dịch')</th>
                        <th class="min-width-100">@lang('Người gán')</th>
                        <th class="min-width-100">@lang('Gán cho')</th>
                        <th class="min-width-100">@lang('CMND')</th>
                        <th class="min-width-100">@lang('Điện thoại')</th>
                         <th class="min-width-100">@lang('Địa chỉ')</th>
                         <th class="min-width-100">@lang('Trạng thái sale')</th>
                        <th class="text-center">@lang('Hành động')</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if (count($clients))
                            @foreach ($clients as $client)
                                <tr>
                                	<td><input type="checkbox" class="select_client" data-id="{{ $client->id }}"></td>
                                	 <td>{{ date('d-m-Y', strtotime($client->import_date)) }}</td>
                                    <td>
                                    <a href="{{ route('clients.edit', $client) }}" class="btn text-primary"
                                           title="@lang('Edit Client')" data-toggle="tooltip" data-placement="top">
                                            {{ $client->name }}
                                    </a>
                                    
                                    </td>
                                    <td>{{ !empty($client->campaign->name)?$client->campaign->name : '' }}</td>
                                    <td>{{ !empty($client->assignByUser)?$client->assignByUser->fullName() : '' }}</td>
                                     <td>{{ !empty($client->assignUser)?$client->assignUser->fullName() : '' }}</td>
                                    <td>{{ $client->national_id }}</td>
                                    <td>{{ !empty($currentUser->role) && $currentUser->role->name == 'Admin'?$client->phone : 'xxxxxxxxx' }}</td>
                                    <td>{{ $client->address }}</td>
                                    <td>{{ !empty($client->saleStage->name)?$client->saleStage->name : ''  }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('clients.edit', $client) }}" class="btn btn-icon text-info"
                                           title="@lang('Edit Client')" data-toggle="tooltip" data-placement="top">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('clients.destroy', $client) }}" class="btn btn-icon text-danger"
                                           title="@lang('Delete Client')"
                                           data-toggle="tooltip"
                                           data-placement="top"
                                           data-method="DELETE"
                                           data-confirm-title="@lang('Please Confirm')"
                                           data-confirm-text="@lang('Are you sure that you want to delete this Client?')"
                                           data-confirm-delete="@lang('Yes, delete it!')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4"><em>@lang('No records found.')</em></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
            {!! $clients->render() !!}
</div>
@stop
@section('scripts')
{!! HTML::script('ckeditor/ckeditor.js') !!}
{!! HTML::script('assets/js/as/client.js') !!}
@include('ckfinder::setup')
@stop

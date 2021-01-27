@extends('layouts.app')

@section('page-title', __('Nhập khách hàng'))
@section('page-heading', __('Nhập khách hàng'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('clients.index') }}">@lang('Khách hàng')</a>
    </li>
    <li class="breadcrumb-item active">
        {{ __('Nhập khách hàng từ exel') }}
    </li>
@stop

@section('content')

@include('partials.messages')

 {!! Form::open(['route' => 'clients.make-import-clients', 'method' => 'POST', 'id' => 'import-clients-form', 'files' => true ]) !!}
 <div class="card">
    <div class="card-body">
        <div class="row">
        	 <div class="col-md-9">
        	    <div class="form-group">
                    <label for="name">@lang('Chọn file')</label>
        	 		<input type="file" class="form-control-file" id="file" name="file">
        	 	</div>
        	 </div>
        </div>
    </div>
</div>
<button type="submit" class="btn btn-primary">
    {{ __('Tải lên') }}
</button>
 @stop
 
 @section('scripts')

   {!! JsValidator::formRequest('ThaoHR\Http\Requests\Client\ImportClientRequest', '#import-clients-form') !!}
@stop
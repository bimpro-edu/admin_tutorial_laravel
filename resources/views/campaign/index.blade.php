@extends('layouts.app')

@section('page-title', __('Chiến dịch'))
@section('page-heading', __('Chiến dịch'))

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">
        @lang('Cài đặt')
    </li>
    <li class="breadcrumb-item active">
        @lang('Chiến dịch')
    </li>
@stop

@section('content')

    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <form action="" method="GET" class="pb-2 mb-3 border-bottom-light">
                <div class="row my-3 flex-md-row flex-column-reverse">
                    <div class="col-md-4 mt-md-0 mt-2">
                        <div class="input-group custom-search-form">
                            <input type="text"
                                   class="form-control input-solid"
                                   name="search"
                                   value="{{ Request::get('search') }}"
                                   placeholder="@lang('Tìm kiếm...')">

                            <span class="input-group-append">
                                <button class="btn btn-light" type="submit" id="search-users-btn">
                                    <i class="fas fa-search text-muted"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <a href="{{ route('campaigns.create') }}" class="btn btn-primary btn-rounded float-right">
                            <i class="fas fa-plus mr-2"></i>
                            @lang('Thêm chiến dịch')
                        </a>
                    </div>
                </div>
            </form>

            <div class="table-responsive" id="users-table-wrapper">
                <table class="table table-striped table-borderless">
                    <thead>
                    <tr>
                        <th class="min-width-100">@lang('Tên')</th>
                         <th class="min-width-100">@lang('Mã')</th>
                        <th class="text-center">@lang('Hành động')</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if (count($Campaigns))
                            @foreach ($Campaigns as $campaign)
                                <tr>
                                    <td>{{ $campaign->name }}</td>
                                    <td>{{ $campaign->code }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('campaigns.edit', $campaign) }}" class="btn btn-icon"
                                           title="@lang('Edit Campaign')" data-toggle="tooltip" data-placement="top">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('campaigns.destroy', $campaign) }}" class="btn btn-icon"
                                           title="@lang('Delete Campaign')"
                                           data-toggle="tooltip"
                                           data-placement="top"
                                           data-method="DELETE"
                                           data-confirm-title="@lang('Please Confirm')"
                                           data-confirm-text="@lang('Are you sure that you want to delete this campaign?')"
                                           data-confirm-delete="@lang('Yes, delete it!')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4"><em>@lang('Không có bản ghi.')</em></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {!! $Campaigns->render() !!}
@stop

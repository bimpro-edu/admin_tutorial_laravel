<div class="card">
    <h6 class="card-header">
        @lang('Báo cáo sale stage của nhân viên')
    </h6>

    <div class="card-body">
        <div class="pt-4 px-3">
             <div class="table-responsive" id="users-table-wrapper">
                <table class="table table-striped table-borderless">
                    <thead>
                    <tr>
                    	<th class="min-width-100">@lang('Tên nhân viên')</th>
                        <th class="min-width-100">@lang('Số khách hàng')</th>
                        <th class="min-width-100">@lang('Số khách hàng đã gọi')</th>
                        <th class="min-width-100">@lang('Số khách hàng chưa gọi')</th>
                        <th class="min-width-100">@lang('Khách hàng không nghe máy')</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if (count($users))
                            @foreach ($users as $user)
                                <tr>
                                	<td>{{ $user->fullName() }}</td>
                                	<td>{{ $user->statistic['total_client'] }}</td>
                                	<td>{{ $user->statistic['total_client_called'] }}</td>
                                    <td>{{ $user->statistic['total_client_not_called'] }}</td>
                                    <td>{{ $user->statistic['total_client_not_answer'] }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5"><em>@lang('Không có dữ liệu.')</em></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
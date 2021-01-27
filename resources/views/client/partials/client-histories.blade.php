<div class="table-responsive" id="users-table-wrapper">
    <table class="table table-striped table-borderless">
        <thead>
        <tr>
            <th class="min-width-100">@lang('Ngày')</th>
            <th class="min-width-100">@lang('Trạng thái sale')</th>
            <th class="text-center">@lang('Ghi chú')</th>
        </tr>
        </thead>
        <tbody>
            @if (count($clientHistories))
                @foreach ($clientHistories as $clientHistory)
                    <tr>
                        <td>{{ $clientHistory->created_at }}</td>
                        <td>{{ $clientHistory->sale_stage }}</td>
                	<td>{{ strip_tags($clientHistory->note) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4"><em>@lang('Không tìm thấy bản ghi.')</em></td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

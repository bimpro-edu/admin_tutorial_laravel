<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="first_name">@lang('Quyền')</label>
            {!! Form::select('role_id', $roles, $edit ? $user->role->id : '',
                ['class' => 'form-control input-solid', 'id' => 'role_id', $profile ? 'disabled' : '']) !!}
        </div>
        <div class="form-group">
            <label for="status">@lang('Trạng thái')</label>
            {!! Form::select('status', $statuses, $edit ? $user->status : '',
                ['class' => 'form-control input-solid', 'id' => 'status', $profile ? 'disabled' : '']) !!}
        </div>
        <div class="form-group">
            <label for="first_name">@lang('Tên')</label>
            <input type="text" class="form-control input-solid" id="first_name"
                   name="first_name" placeholder="@lang('First Name')" value="{{ $edit ? $user->first_name : '' }}">
        </div>
        <div class="form-group">
            <label for="last_name">@lang('Họ')</label>
            <input type="text" class="form-control input-solid" id="last_name"
                   name="last_name" placeholder="@lang('Last Name')" value="{{ $edit ? $user->last_name : '' }}">
        </div>
        <div class="form-group">
            <label for="last_name">@lang('Latitude')</label>
            <input type="text" class="form-control input-solid" id="lat"
                   name="lat" placeholder="@lang('Google map latitude')" value="{{ $edit ? $user->lat : '' }}">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="birthday">@lang('Ngày sinh')</label>
            <div class="form-group">
                <input type="text"
                       name="birthday"
                       id='birthday'
                       value="{{ $edit && $user->birthday ? $user->present()->birthday : '' }}"
                       class="form-control input-solid" />
            </div>
        </div>
        <div class="form-group">
            <label for="phone">@lang('Điện thoại')</label>
            <input type="text" class="form-control input-solid" id="phone"
                   name="phone" placeholder="@lang('Phone')" value="{{ $edit ? $user->phone : '' }}">
        </div>
        <div class="form-group">
            <label for="name">@lang('Phân cho')</label>
            {!! Form::select('leader_id', $users, $edit ? $user->leader_id : '', ['class' => 'form-control form-control-chosen', 'id' => 'leader_id']) !!}
        </div>
        <div class="form-group">
            <label for="address">@lang('Địa chỉ')</label>
            <input type="text" class="form-control input-solid" id="address"
                   name="address" placeholder="@lang('Address')" value="{{ $edit ? $user->address : '' }}">
        </div>
        <div class="form-group">
            <label for="address">@lang('Quốc gia')</label>
            {!! Form::select('country_id', $countries, $edit ? $user->country_id : '', ['class' => 'form-control input-solid']) !!}
        </div>
        <div class="form-group">
            <label for="last_name">@lang('Longitude')</label>
            <input type="text" class="form-control input-solid" id="lat"
                   name="long" placeholder="@lang('Google map longitude')" value="{{ $edit ? $user->long : '' }}">
        </div>
    </div>

    @if ($edit)
        <div class="col-md-12 mt-2">
            <button type="submit" class="btn btn-primary" id="update-details-btn">
                <i class="fa fa-refresh"></i>
                @lang('Cập nhật chi tiết')
            </button>
        </div>
    @endif
</div>

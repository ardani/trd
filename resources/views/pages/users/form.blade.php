@if(session('message'))
    {!! alerts('success',session('message')) !!}
@endif
<fieldset class="form-group">
    <label class="form-control-label">Username <span class="text-danger">*</span></label>
    <input type="text" name="username" class="form-control"
           value="{{ $model ? $model['username'] : old('username') }}"
           data-validation="[NOTEMPTY]"
           placeholder="username">
        {{ csrf_field() }}
</fieldset>
<fieldset class="form-group">
    <label class="form-control-label">Email <span class="text-danger">*</span></label>
    <input type="text" class="form-control" name="email"
           value="{{ $model ? $model['email'] : old('email') }}"
           data-validation="[NOTEMPTY]"
           placeholder="email">
</fieldset>
<fieldset class="form-group">
    <label class="form-control-label">Password <span class="text-danger">*</span></label>
    <input type="text" class="form-control" name="password"
           value=""
           data-validation="[NOTEMPTY]"
           placeholder="password">
</fieldset>
<fieldset class="form-group ">
    <label class="form-control-label">Role</label>
    <select name="parent" class="form-control">
        @foreach($roles as $role)
            @if($role->id == safe_array($model,'role_id'))
                <option selected value="{{$role->id}}">{{$role->display_name}}</option>
            @else
                <option value="{{$role->id}}">{{$role->display_name}}</option>
            @endif
        @endforeach
    </select>
</fieldset>
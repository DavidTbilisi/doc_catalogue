<div class="col-3">
    <div class="form-check form-switch">
        <input class="form-check-input" {{$disabled?"disabled":''}} name="{{$permission->power}}" type="checkbox" role="switch" id="{{$permission->const_name.$perms['group']->alias}}" {{$disabled?"":"checked"}}>
        <label class="form-check-label"  {!! $disabled?'title="შეზღუდულია ჯგუფებიდან"':'' !!} for="{{$permission->const_name.$perms['group']->alias}}">{{$permission->name}} </label>
    </div>
</div>

@foreach($components as $component)
    <div class="col-md-4">
        <fieldset class="form-group">
            <label class="form-control-label">{{$component->name}}</label>
            <input type="text" class="form-control" name="component[{{$component->code}}]" value="" />
        </fieldset>
    </div>
@endforeach
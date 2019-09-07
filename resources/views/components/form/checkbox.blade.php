<input type="hidden" value="0" name="{{ $name }}">
<div class="row form-group{{ $errors->has($name) ? ' has-error' : '' }}">
    @if (isset($label))
        <label for="{{ $name }}" class="col-md-3 control-label">{{ $label }}</label>
    @endif

    <div class="col-md-3">
        <input
            value="1"
            name="{{ $name }}"
            id="{{ $name }}"
            type="checkbox"
            class="form-control"
            @if (old($name))
                checked="checked"
            @elseif (isset($object) and isset($object->{$name}) and $object->{$name} == 1))
                checked="checked"
            @endif
            {!! isset($attributes) ? $attributes : '' !!}
        >
    

        @if ($errors->has($name))
            <span class="help-block">
                <strong>{{ $errors->first($name) }}</strong>
            </span>
        @endif
    </div>
</div>
<div class="row form-group{{ $errors->has($name) ? ' has-error' : '' }}">
    @if (isset($label))
        <label for="{{ $name }}" class="col-md-3 control-label">{{ $label }}</label>
    @endif

    <div class="col-md-3">
        <input id="{{ $name }}[{{ $index }}]" 
            type="number"
            class="form-control"
            name="{{ $name }}[{{ $index }}]"
            placeholder="{{ isset($placeholder) ? $placeholder : '' }}"
            value="{{ old($name.'.'.$index) ?: (isset($object) ? $object->{$name}->{$index} : '') }}"
            {!! isset($attributes) ? $attributes : '' !!}>

        @if ($errors->has($name))
            <span class="help-block">
                <strong>{{ $errors->first($name) }}</strong>
            </span>
        @endif
    </div>
</div>
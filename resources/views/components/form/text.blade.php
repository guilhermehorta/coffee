<div class="row form-group{{ $errors->has($name) ? ' has-error' : '' }}">
    @if (isset($label))
        <label for="{{ $name }}" class="col-md-3">{{ $label }}</label>
    @endif

    <div class="col-md-9">
        <input id="{{ $name }}" 
            type="text"
            class="form-control"
            name="{{ $name }}"
            placeholder="{{ isset($placeholder) ? $placeholder : '' }}"
            value="{{ old($name) ?: (isset($object) ? $object->{$name} : '') }}"
            {!! isset($attributes) ? $attributes : '' !!}>

        @if ($errors->has($name))
            <span class="help-block">
                <strong>{{ $errors->first($name) }}</strong>
            </span>
        @endif
    </div>
</div>
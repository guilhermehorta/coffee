<div class="input-number-wrapper flex-container-row justify-end">
    @if (isset($label))
        <label for="{{ $name }}" class="">{{ $label }}</label>
    @endif

    <input id="{{ $name }}" 
        type="number"
        class=""
        name="{{ $name }}"
        placeholder="{{ isset($placeholder) ? $placeholder : '' }}"
        value="{{ old($name) ?: (isset($object) ? $object->{$name} : '0') }}"
        {!! isset($attributes) ? $attributes : '' !!}>

</div>

@if ($errors->has($name))
    <span class="help-block">
        <strong>{{ Corrigir }}</strong>
    </span>
@endif

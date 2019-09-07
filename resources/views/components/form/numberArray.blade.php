<div class="input-number-wrapper flex-container-row justify-center">
    @if (isset($label))
        <label for="{{ $name }}[{{ $index }}]" class="">{{ $label }}@if (isset($showIndex)){{ $index }}@endif</label>
    @endif
    
    <input id="{{ $name }}[{{ $index }}]" 
        type="number"
        class=""
        name="{{ $name }}[{{ $index }}]"
        placeholder="{{ isset($placeholder) ? $placeholder : '' }}"
        value="{{ old($name.'.'.$index) ?: (isset($object) ? $object->{$name}->{$index} : '') }}"
        {!! isset($attributes) ? $attributes : '' !!}>

</div>

@if ($errors->has($name.'.'.$index))
    <span class="help-block">
        <strong>{{ 'Corrigir' }}</strong>
    </span>
@endif


@if ($errors->has($name.'.'.$index))
    <span class="help-block">
        <strong>{{ $errors->first($name.'.'.$index) }}</strong>
    </span>
@endif

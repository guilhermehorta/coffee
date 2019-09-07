<input type="hidden" value="0" name="{{ $name }}[{{ $index }}]">

<div class="checkbox-wrapper flex-container-row-reverse justify-center">
    <input
        value="1"
        name="{{ $name }}[{{ $index }}]"
        id="{{ $name }}[{{ $index }}]"
        type="checkbox"
        class=""
        @if (old($name.'.'.$index))
            checked="checked"
        @elseif (isset($object) and isset($object->{$name}->{$index}) and $object->{$name}->{$index} == 1)
            checked="checked"
        @endif
        {!! isset($attributes) ? $attributes : '' !!}
    >
    <label for="{{ $name }}[{{ $index }}]" class="">
        @if (isset($label))
            {{ $label }}{{ $index }}
        @endif
    </label>
</div>

@if ($errors->has($name.'.'.$index))
    <span class="help-block">
        <strong>Corrigir</strong>
    </span>
@endif

{{--
    @if ($errors->has($name.'.'.$index))
    <span class="help-block">
        <strong>{{ $errors->first($name.'.'.$index) }}</strong>
    </span>
    @endif
--}}
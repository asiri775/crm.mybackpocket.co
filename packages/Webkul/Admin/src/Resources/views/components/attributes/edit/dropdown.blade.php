@php
    $options = json_decode($attribute->field_items, true);
    if($options==null){
        $options = [];
    }
@endphp

<x-admin::form.control-group.control
    type="select"
    id="{{ $attribute->code }}"
    name="{{ $attribute->code }}"
    rules="{{ $validations }}"
    :label="$attribute->name"
    :placeholder="$attribute->name"
    :value="old($attribute->code) ?? $value"
>
    @foreach ($options as $option)
        <option value="{{ $option['value'] }}">
            {{ $option['label'] }}
        </option>
    @endforeach
</x-admin::form.control-group.control>
@props([
    'dataTableId' => null,
    'options' => ['10', '25', '50', 'All'],
])

<select id="{{ $dataTableId }}-pageLength" class="form-select"
     {{ $attributes->merge(['class' => '']) }}>
    @foreach ($options as $option)
        <option value="{{ $option }}">
            {{ $option }}
        </option>
    @endforeach
</select>

